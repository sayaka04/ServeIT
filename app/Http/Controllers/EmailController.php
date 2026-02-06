<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailSenderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function emailSendNotification($email, $subject, $message, $details, $view)
    {
        Mail::to($email)->send(new MailSenderNotification(
            $subject,
            $message,
            $details,
            $view
        ));
    }
}















//OLD CODE SAMPLE
    // public function emailSendVerification($email, $subject, $message)
    // {
    //     Log::info('ENABLE_EMAIL_SENDER: ' . config('custom.enable_email_sender'));
    //     Log::info('App Name: ' . getenv('APP_NAME'));
    //     Log::info('Env file loaded: ' . file_exists(base_path('.env')));


    //     if (config('custom.enable_email_sender')) {
    //         Mail::to($email)->send(new MailSenderNotification(
    //             $subject,
    //             $message
    //         ));
    //         Log::info("\n----Send email is passed!----\n");
    //     } else {
    //         Log::info("\n----False???!!----\n");
    //     }
    // }