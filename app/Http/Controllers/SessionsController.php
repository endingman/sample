<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct($value = '')
    {
        $this->middleware('guest', ['only' => ['create']]);
    }
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        // attempt 方法会接收一个数组来作为第一个参数，该参数提供的值将用于寻找数据库中的用户数据。因此在上面的例子中，用户信息将使用 email 字段的值在数据库中进行查找，如果用户被找到，在将 password 的值进行哈希加密并与数据库中已加密过的密码进行匹配，如果匹配后两个值完全一致，则会创建一个通过认证的会话给用户。会话获取到之后，即视为用户登录成功。当用户身份认证成功 attempt 方法会返回 true，反之则返回 false。

        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来！');
                return redirect()->intended(route('users.show', [Auth::user()]));
                // redirect() 实例提供了一个 intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上，并接收一个默认跳转地址参数，当上一次请求记录为空时，跳转到默认地址上
                //  Laravel 提供的 Auth::user() 方法来获取当前登录用户的信息，并将数据传送给路由。
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }

        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }

        return;
    }

    public function destroy()
    {
        Auth::logout(); //laravel自带退出
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
