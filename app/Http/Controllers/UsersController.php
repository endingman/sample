<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:50', //required必填，max最大长度
            'email'    => 'required|email|unique:users|max:255', //unique:users验证用户使用的注册邮箱是否已被它人使用
            'password' => 'required|confirmed',
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }
}
