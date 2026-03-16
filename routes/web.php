<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-mail', function () {

    Mail::raw('Test Mail from Laravel', function ($message) {
        $message->to('ai.priyobabu@gmail.com')
                ->subject('Laravel Mail Test');
    });

    return "Mail Sent Successfully";
});
