<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post; //This is the post mode represents the [posts table]
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $post;
    private $user;

    public function __construct(Post $post, User $user)
    {
       $this->post = $post;
       $this->user = $user;
    }

    # Get all the posts of the users the AUTH USER is following
    private function getHomePosts(){
        $all_posts = $this->post->latest()->get(); //SAME AS: SELECT * FROM posts ORDER  BY created_at DESC
        $home_posts = []; //this will be a container that will hold the filtered posts

        foreach ($all_posts as $post) {
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id) {
                $home_posts[] = $post;
            }
        }

        return $home_posts; //note: this $home_posts now contains the post of the user being followed by by the logged-in user and the posts of the current logged-in user
    }

    # Get users that the AUTH USER is not following
    public function getSuggestedUsers(){
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach ($all_users as $user) {
            // if the AUTH USER is NOT following the $user, save the $user in the $suggested_users.
            if (!$user->isFollowed()) {
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 5);
        # array_slice(x,y,z)

    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    # Use this method to display post details into the homepage
    public function index()
    {
        $home_posts         = $this->getHomePosts();
        $suggested_users    = $this->getSuggestedUsers();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }


    // this will receive the user being search coming from the form in app.blade.php line40~
    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%'. $request->search . '%')->get();
        # Mr.John, John Smith, John Smith III ---> '%john%'　←　'1個目の % = 苗字(頭)のJohn', '2個目の % = 名前のJohn'

        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
