<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorNetworkController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/doctor/network-aggregates/{id}', [DoctorNetworkController::class, 'aggregates']);