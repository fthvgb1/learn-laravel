<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['create', 'store', 'confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => 'create'
        ]);
    }

    public function index()
    {
        return view('users.index', ['users' => User::paginate(10)]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(20);
        return view('users.show', compact('user', 'statuses'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50|min:3',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|min:6'
        ]);
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        User::sendEmailConfirmationTo($user);

        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');

        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '删除成功！');
        return back();
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'password' => 'nullable|confirmed|min:6',
        ]);
        $user->name = $request->get('name');
        if (!empty($request->get('password'))) {
            $user->password = bcrypt($request->get('password'));
        }
        $user->update();
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * 关注的用户
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followings(User $user)
    {
        $users = $user->followings()->orderByDesc('id')->paginate(20);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    /**
     * 用户的粉丝列表
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers(User $user)
    {
        $users = $user->followers()->orderBy('id', 'desc')->paginate(20);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
