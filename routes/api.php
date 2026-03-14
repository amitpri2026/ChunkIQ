<?php

use App\Http\Controllers\Api\JobCallbackController;
use Illuminate\Support\Facades\Route;

// Called by Azure Function Apps when a job completes (token-authenticated, no session)
Route::post('/jobs/{token}/callback', JobCallbackController::class)->name('api.jobs.callback');
