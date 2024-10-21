<?php

namespace App\Http\Controllers;

use App\Mail\NotifMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(){
        $mailData = [
            'title' => 'Notification for you',
            'body' => 'test'
        ];
    
        Mail::to('ezrajfpakpahan22@gmail.com')->send(new NotifMail($mailData));

        dd("Email terkirim");
    }
}
