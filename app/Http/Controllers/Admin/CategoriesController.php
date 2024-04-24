<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post; //postのために追加

class CategoriesController extends Controller
{
    private $category;
    private $post; //postのために追加

    public function __construct(Category $category,  post $post){ //categoryから後ろpostのために追加

        $this->category = $category;
        $this->post = $post; //postのために追加

    }

    public function index(){
        $all_categories = $this->category->orderby('updated_at', 'desc')->paginate(5);

        $uncategorized_count = 0; //postのために追加.line24-32
        $all_posts = $this->post->all(); //SAME AS: SELECT * FROM posts;　


        foreach ($all_posts as $post){
            if($post->categoryPost->count() == 0){ //if this return true, then the post don't category
                $uncategorized_count++; //increment the count
            }
        }

        return view('admin.categories.index')
           ->with('all_categories', $all_categories)
           ->with('uncategorized_count', $uncategorized_count);// postのために追加　categories/index.blade.php line65　と繋がっている
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name'
        ]);

        $this->category->name = ucwords(strtolower( $request->name));
        //
        // strtolower() -> convert the word in Lowercase
        // ucwords() -> to capitalize the first letter of the word
        //
        $this->category->save();

        return redirect()->back();
    }

    // 編集するかキャンセルするかポップアップが出てきた時の編集ボタンを押した後の動作
    public function update(Request $request, $id){
        $request->validate(
            [
                'new_name' => 'required|min:1|max:50|unique:categories,name,' . $id
            ]
        );

            $category = $this->category->findOrFail($id);
            $category->name = ucwords(strtolower($request->new_name));
            $category->save();

            return redirect()->back();
    }


# CHALLENGE!!
    # Activity: Admin dashboard delete button
    # 1. create the modal         => action.blade.php in modalフォルダー
    # 2. create a method destroy　=> web.php
    # 3. create the route         => action.blade.php in modalフォルダー
    # AND CategoriesController.phpの　56～68の部分をcreate
    # 4. use the route to test if working!



   // 削除するかキャンセルするかポップアップが出てきた時の削除ボタンを押した後の動作
//    自分でやったコード
    // public function destroy(Request $request, $id){
    //     $request->validate(
    //         [
    //             'new_name' => 'required|min:1|max:50|unique:categories,name,' . $id
    //         ]
    //     );

    //         $category = $this->category->findOrFail($id);
    //         $category->name = ucwords(strtolower($request->new_name));
    //         $category->save();

    //         return redirect()->back();
    // }

//   先生のアンサーコード！
public function destroy($id){

    $this->category->destroy($id);

    return redirect()->back();

}
}
