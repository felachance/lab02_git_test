<?php

use App\Models\TimeOffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReplacementRequestController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\TimeOffRequestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(BranchController::class)->group(function () {
    Route::get('/branches', 'index')->name('indexApi');
    Route::get('/branches/{id}', 'show')->name('showApi');
});
Route::controller(ShiftController::class)->group(function () {
    Route::get('/shift/{id}', 'show')->name('shiftAPI.show');
    Route::get('/shifts/availableEmployees/{id_branch}/{date}/{startTime}/{endTime}', 'getAvailableEmployees')->name('shiftAPI.getAvailableEmployees');
    Route::get('/schedule/checkAvailability', 'checkAvailability')->name('shiftAPI.checkAvailability');
    Route::get('/schedule/{branch}/{week}/{user}', 'scheduleByWeekByUser')->name('shiftAPI.scheduleByWeekByUser');
    Route::get('/schedule/{week}/{user}', 'scheduleByWeekByUserAll')->name('shiftAPI.scheduleByWeekByUserAll');
    Route::get('/shifts/future/{id}', 'getFutureShifts')->name('shiftAPI.getFutureShifts');
    Route::get('/shift/next/{id}', 'getNextShift')->name('shiftAPI.getNextShift');
    Route::get('/shifts/futurenorequests/{id}', 'getFutureShiftsNoRequests')->name('shiftAPI.getFutureShiftsWithNoReplacementRequests');
});

Route::controller(AvailabilityController::class)->group(function(){
    Route::get('/availability/{id}', 'index')->name('api.availability.index');
    Route::get('/availability', 'indexAll')->name('api.availability.indexAll');
    Route::post('/availability', 'store')->name('api.availability.store');
    Route::put('/availability', 'update')->name('api.availability.update');
    Route::delete('/availability', 'destroy')->name('api.availability.destroy');
});

Route::controller(ReplacementRequestController::class)->group(function () {
    Route::get('/replacements', 'index')->name('api.replacements.index');
    Route::get('/replacements/enattente/{id}', 'getReplacementsEnAttente')->name('api.replacements.getReplacementsEnAttente');
    Route::get('/replacementswithoutuser/{id}', 'indexWithoutUser')->name('api.replacements.getReplacementRequestsWithoutUser');
    Route::get('/replacements/{id}', 'getReplacementRequestsByUser')->name('api.replacements.getReplacementRequestsByUser');
    Route::post('/replacements', 'store')->name('api.replacements.store');
    Route::put('/replacements/{id}', 'update')->name('api.replacements.update');
    Route::patch('/replacements/cancel/{id}', 'cancel')->name('api.replacements.cancel');
    Route::patch('/replacements/accept/{id}/{userId}', 'accept')->name('api.replacements.updateStatus');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user/{id}', 'show')->name('userAPI.show');
    Route::get('/user', 'getResearch')->name('userAPI.getResearch');
    Route::put('/user/update/{id}', 'update')->name('userAPI.update');
});

Route::middleware('guest')->group(function () {
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('loginAPI');
});

Route::controller(RegisteredUserController::class)->group(function() {
    Route::post('/token', 'show')->name('token');
});

Route::controller(TimeOffRequestController::class)->group(function () {
    Route::get('/timeOffRequest/{id}', 'show')->name('timeOffRequestAPI.show');
    Route::get('/timeOffRequest', 'index')->name('timeOffRequestAPI.index');
    Route::post('/timeOffRequest', 'store')->name('timeOffRequestAPI.store');
    Route::put('/timeOffRequest/{id}', 'update')->name('timeOffRequestAPI.update');
    Route::delete('/timeOffRequest/{id}', 'destroy')->name('timeOffRequestAPI.destroy');
    Route::patch('/timeoff/status/{timeoff}', 'updateStatus')->name('timeoffs.updateStatus');
    Route::get('/timeOffRequest/user/{user_id}', [TimeOffRequestController::class, 'getByUser'])->name('timeOffRequestAPI.byUser');
});
