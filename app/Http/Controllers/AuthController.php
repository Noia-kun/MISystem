<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = DB::connection('dtr')
            ->table('tbl_admin')
            ->where('username', $request->username)
            ->first();
        
        // if ($user && md5($request->password) === $user->password) {
        if ($user && strtolower($user->password) === strtolower(md5($request->password))) {
            session([
                'logged_in' => true,
                'admin_id' => (int) $user->admin_id,
                'username' => $user->username
            ]);

            return redirect(match((int) $user->admin_id) {
                1       => '/dashboard',
                2       => '/user-dashboard',
                3       => '/admin-dashboard',
                4       => '/admin-dashboard2',
                default => '/dashboard',
            });
        }

        return redirect('/login')->with('error', 'Invalid login. Wrong username or password.');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
