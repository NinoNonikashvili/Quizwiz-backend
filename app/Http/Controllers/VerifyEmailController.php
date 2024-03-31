<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function index(Request $request)
    {
        /**
         * use $request['email']; to identify user and set email_verified to true
         */
        
        if (! $request->hasValidSignature()) {

            
            return redirect()->away(env('FRONTEND_URL').'/login?expired=true&email='.$request['email']);
        }

        $user = User::where('email', $request['email'])->first();
        $user->email_verified_at = now();
        $user->save();
        return redirect()->away(env('FRONTEND_URL').'/login?expired=false');
    }

    public static function generateTemporaryUrl($urlName, $expiration, $email){
        
        return URL::temporarySignedRoute($urlName, $expiration, ['email' => $email]);

    }

    public function resendEmail(Request $request){

        $email = $request['email'];
        $link = $this::generateTemporaryUrl('verify-email', now()->addMinutes(120), ['email' => $email]);
        //send email
        //if success
        return response('Verification link is sent to your email '. $link , 200)->header('Content-Type', 'application/json');
        //else return 500
    }
}
