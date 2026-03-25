<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

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

Route::get('/test-redis', function () {
    Cache::put('test_key', 'Redis Working!', 60);
    return Cache::get('test_key');
});