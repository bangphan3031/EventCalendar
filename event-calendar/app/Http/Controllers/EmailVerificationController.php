<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return [
                'message' => 'Already verified'
            ];
        }
        $request->user()->sendEmailVerificationNotification();
        return ['status' => 'verification-link-send'];
    }
    public function verify(EmailVerificationRequest $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return [
                'message' => 'Email already verified'
            ];
        }
        if($request->user()->markEmailAsVerified()){
            event(new Verified($request->user()));
        }
        return [
            'message' => 'Email has been verified'
        ];
    }
}
