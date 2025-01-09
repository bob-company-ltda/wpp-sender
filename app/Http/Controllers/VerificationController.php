<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function verify(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/'); // Or any other redirect route
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect('/login'); // Or any other redirect route
    }
    
    public function resend(Request $request)
    {
        $user = $request->user();

        // Get the current number of resend attempts from the cache
        $resendAttempts = Cache::get('resend_attempts_' . $user->id, 0);

        // Check if the user has reached the maximum number of resend attempts
        if ($resendAttempts >= 3) { // Change the number as per your requirement
            // Calculate the cooldown period based on the number of attempts
            $cooldown = ($resendAttempts <= 5) ? 120 : (($resendAttempts <= 10) ? 600 : 3600);

            return response()->json([
                'message' => 'You have exceeded the maximum number of resend attempts. Please try again later.',
                'cooldown' => $cooldown, // Return the cooldown period in seconds
            ], 400);
        }

        // Check if the cooldown period has not elapsed
        $lastResendTime = Cache::get('last_resend_time_' . $user->id, null);
        if ($lastResendTime !== null && now()->diffInMinutes($lastResendTime) < 2) {
            return response()->json([
                'message' => 'Please wait for 2 minutes before resending again.',
                'cooldown' => 120, // Return the remaining cooldown period in seconds
            ], 400);
        }

        // Increment the number of resend attempts and update the cache
        Cache::put('resend_attempts_' . $user->id, $resendAttempts + 1);
        // Update the last resend time in the cache
        Cache::put('last_resend_time_' . $user->id, now());

        // Send the verification email
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email sent successfully.',
        ]);
    }
}
