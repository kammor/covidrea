<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Mailgun webhook (feedback from Mailgun)
Route::post('webhook/mailgun', 'WebhookMailgunController@index');
