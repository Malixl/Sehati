<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('pages.landing');
});

// Consent Page
Route::get('/consent', function () {
    return view('pages.consent');
});

// Respondent Form
Route::get('/respondent', function () {
    return view('pages.respondent');
});

// Questionnaire Page
Route::get('/questionnaire', function () {
    return view('pages.questionnaire');
});

// Result Page (Placeholder for later)
Route::get('/result', function () {
    return view('pages.result');
});

// Map Page (Faskes Terdekat)
Route::get('/map', function () {
    return view('pages.map');
});

// Design System Test Page
Route::get('/test', function () {
    return view('test');
});
