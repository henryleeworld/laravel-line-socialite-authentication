<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Exception;
use Socialite;
  
class LineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToLine()
    {
        return Socialite::driver('line')->redirect();
    }
      
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleLineCallback()
    {
        try {
            $lineUser = Socialite::driver('line')->stateless()->user();
            $user = User::updateOrCreate([
                'line_id'     => $lineUser->id,
                'line_avatar' => $lineUser->avatar,
            ], [
                'name'        => $lineUser->name,
                'email'       => $lineUser->email,
                'password'    => encrypt('123456dummy')
            ]);
            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

