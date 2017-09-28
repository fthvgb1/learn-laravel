<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Policies\StatusPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('status.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:255'
        ]);
        Auth::user()->statuses()->create([
            'content' => $request->get('content'),
        ]);
        session()->flash('success', '发布成功！');
        return back();
    }

    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return back();
    }
}
