<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; // Authentication class-- this will handle the user authentication
use Illuminate\Http\Request;
use App\Models\Category; //this represents the categories table
use App\Models\Post;    // this represents the posts table

class PostController extends Controller
{
    # Define the properties
    private $post;
    private $category;

    # Define the constructor
    public function __construct(Post $post, Category $category){
        $this->post = $post;
        $this->category = $category;
    }

    public function create(){
        $all_categories = $this->category->all();
        #Same as: SELECT * FROM categories;

        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    # The $request holds our data coming from the form in create post page
    # Data: category[1, 7, 8]
    public function store(Request $request){
        # 1. Validate the first
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        # 2. Save the post details to the post table
        $this->post->user_id = Auth::user()->id; //owner of the post
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save(); //post id 1

        # 3. Save the categories into the category_post (PIVOT) table
        foreach ($request->category as $category_id) { //$category_id[1,7,8]
            $category_post[] = ['category_id' => $category_id]; //key:value pair
            #$category_post[1]    <------------  #$category_id[1] - Travel
            #$category_post[7]    <------------  #$category_id[7] - Career
            #$category_post[8]    <------------  #$category_id[8] - Sport

            # Explanation:
            # Data --> $request->category[1, 7, 8]; -- coming from the form

        }
        $this->post->categoryPost()->createMany($category_post); //1,7,8
        # $category_post[
        #    ['post_id' => 1, 'category_id' => 1],
        #    ['post_id' => 1, 'category_id' => 7],
        #    ['post_id' => 1, 'category_id' => 8,]
        # ]

        # Explanation:
        # categoryPost() --> category_post PIVOT table
        #
        # post_id           category_id
        # ------------------------------
        # 1                    1
        # 1                    7
        # 1                    8


        # 4. Go back to the homepage
        return redirect()->route('index');
    }

    # Method to retrieve the post to display
    public function show($id){
        $post = $this->post->findOrFail($id); //Same as: SELECT * FROM posts WHERE id = $id
        return view('users.posts.show')->with('post', $post);
    }

    # this method retrieves the post details for edit
    public function edit($id){
        $post = $this->post->findOrFail($id); //Same as: SELECT * FROM posts WHERE id = $id

        # for security, If the user is not the owner of the post, redirect them to homepage
        if (Auth::user()->id != $post->user->id) {
            return redirect()->route('index'); //homepage
        }

        # Retrieve all the categories from the categories table
        $all_categories = $this->category->all(); //Same as: SELECT * FROM categories;

        # Get all the categories under this post, and save it in an array
        $selected_categories = [];
        #         2 ---> 3,5,6
        foreach ($post->categoryPost as $category_post) {
            $selected_categories[] = $category_post->category_id;
            #$selected_categories[3]
            #$selected_categories[5]
            #$selected_categories[6]
        }

        return view('users.posts.edit')
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);
    }


    # $id is the id of the post we want to update
    public function update(Request $request, $id){
        # 1. Validate the data first before saving to the database
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        # 2. Update the post details
        $post = $this->post->findOrFail($id); //SELECT * FROM posts WHERE id = $id
        $post->description = $request->description;

        # check if the there is a new image uploaded
        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        # 3. Delete all records from category_post related to this post
        $post->categoryPost()->delete();
        //Use the ralatioinship Post::categoryPost() so select the records ralated to this post
        //Equivalent to: DELETE FROM category_post WHERE id = $id

        # 4. Save the new categories to the category_post table
        # Post 2 ----> 1,2,3
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
            #$category_post[1]
            #$category_post[2]
            #$category_post[3]

        }
        #Post 2 ----> 1,2,3
        $post->categoryPost()->createMany($category_post);

        # $post = $this->post->findOrFail(2)
        # $post->categoryPost()->delete();

        # ARRAY
        # $category_post[
        #   ['category_id' => 1, 'post_id' => 2]
        #   ['category_id' => 2, 'post_id' => 2]
        #   ['category_id' => 3, 'post_id' => 2]
        # ]


        # 5. Redirect to show post page (To confirm the update)
        return redirect()->route('post.show', $id);
    }

    # Delete a post
    public function destroy($id){
        $post = $this->post->findOrFail($id);
        $post->forceDelete(); //will completely delete the posts
        return redirect()->route('index'); //homepage

        // $this->post->destroy($id); //SAME AS: DELETE FROM posts WHERE id = $id
        // return redirect()->route('index'); //homepage
    }
}
