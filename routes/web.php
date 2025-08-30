<?php

use Illuminate\Support\Facades\Route;

// Redirect to the front-end URL
Route::get('/', fn () => config('app.name'));
