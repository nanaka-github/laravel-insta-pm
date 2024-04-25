<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\models\User;


class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')->with('user', $user);
    }

    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.profile.edit')
          ->with('user', $user);
    }

    public function update(Request $request)
    {
            $request->validate([
                'name'=> 'required|min:1|max:50',
                'email'=> 'required|email|max:50|unique:users,email,' . Auth::user()->id,
                'avatar'=> 'mimes:jpeg,jpg,png,gif|max:1048',
                'introduction'=> 'max:100'
            ]);

            $user = $this->user->findOrFail(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->introduction = $request->introduction;

            if($request->avatar) {
                $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
            }

            #save
            $user->save();

            #redirect
            return redirect()->route('profile.show', Auth::user()->id);

            #Update Password
            $user = $this->user->findOrFail(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->introduction = $request->introduction;
            if(!password_verify($request->old_password, $user->password))
            {
                return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect.']);
            }
            $user->password = password_hash($request->new_password, PASSWORD_DEFAULT);
    }

    public function followers($id){

          # Important note: anyone can view anyone's follower's lists the id parameter ($id) is the id of the user that you want to view

        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }
}
