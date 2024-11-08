<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// In your routes/web.php or routes/api.php
Route::get('/check-db-connection', function () {
    return DB::connection()->getPDO()->getAttribute(PDO::ATTR_CONNECTION_STATUS);
});
