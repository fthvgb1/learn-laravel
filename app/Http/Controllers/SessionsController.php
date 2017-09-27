<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    use Authenticatable;

    public function __construct()
    {
        $this->middleware('guest', [
            'only' => 'create'
        ]);
    }

    public function create()
    {

        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required'
        ]);

        $credentials = [
            'email'=>$request->get('email'),
            'password'=>$request->get('password')
        ];
        if(Auth::attempt($credentials,$request->has('remember'))){
            return redirect()->intended(route('users.show', [Auth::user()]));
        }else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
