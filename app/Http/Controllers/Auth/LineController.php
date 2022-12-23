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
            $user = Socialite::driver('line')->stateless()->user();
            $finduser = User::where('line_id', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard');
            }else{
                // email scope isn't returned as a value of the scope property even if access to it has been granted.
                $newUser = User::create([
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'line_id'     => $user->id,
                    'line_avatar' => $user->avatar,
                    'password'    => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
     
                return redirect('/dashboard');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

