<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(User $user)
    {
        if (Auth::user()->id === $user->id) {
            session()->flash('你不能自己关注自己！');
            back();
        }
        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
            session()->flash('success', '关注成功！');
        }
        return redirect()->route('users.show', [$user->id]);

    }

    public function destroy(User $user)
    {
        if (Auth::user()->id === $user->id) {
            session()->flash('你不能自己取消关注自己！');
            back();
        }
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
            session()->flash('success', '取消关注成功！');
        }
        return redirect()->route('users.show', [$user->id]);
    }
}
