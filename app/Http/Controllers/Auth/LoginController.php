<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  function showLoginForm()
  {
    return view('auth.login');
  }

  function login(Request $request)
  {
    $this->validateLogin($request);
    if ( Auth::attempt(['user_name'=>$request->user, 'password'=>$request->password, 'active'=>1]) ) {
      return redirect()->route('main');
    }

    return back()
            ->withErrors(['user_name'=>trans('auth.failed'), 'password'=>trans('auth.failed')])
            ->withInput(request(['user_name']));
  }

  protected function validateLogin(Request $request)
  {
    $this->validate($request, [
            'user_name' => 'required|string',
            'password' => 'required|string'
    ]);
  }
}
