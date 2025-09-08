<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplacementRequestController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Shift;
use App\Models\TimeOffRequest;
use App\Models\Availability;
use App\Models\ReplacementRequest;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\TimeOffRequestController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {

    $shiftsWeek = Shift::where('date', '>=', now()->startOfWeek())
        ->where('date', '<=', now()->endOfWeek());

    $nbHours =0;
    foreach ($shiftsWeek->get() as $shift) {
        $start = \Carbon\Carbon::parse($shift->start_time);
        $end = \Carbon\Carbon::parse($shift->end_time);
        $nbHours += $start->diffInHours($end);
    }

    return view('dashboard', [
        'nbShifts' => $shiftsWeek->count(),
        'nbHours' => $nbHours,
        'nbUnapprovedTimeOffRequests' => TimeOffRequest::where('type_id', '=', '1')->count(),
        'nbEmployees' => User::where('active', '1')->count(),
        'nbBranches' => Branch::count(),
        'branch' => Branch::where('is_actif', 1)->first(),
        'nbReplacements' => ReplacementRequest::where('id_replacement_request_type', '=', '2')->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/replacements', [ReplacementRequestController::class, 'store'])->name('replacements.store');
    Route::get('/replacements', [ReplacementRequestController::class, 'index'])->name('replacements.index');
    Route::get('/replacements/create', [ReplacementRequestController::class, 'create'])->name('replacements.create');
    Route::put('/replacements/{id}', [ReplacementRequestController::class, 'update'])->name('replacements.update');
    Route::delete('/replacements/{id}', [ReplacementRequestController::class, 'destroy'])->name('replacements.destroy');
    Route::get('/replacements/{id}', [ReplacementRequestController::class, 'show'])->name('replacements.show');

    Route::controller(AvailabilityController::class)->group(function(){
        Route::get('/availability/{id}', 'index')->name('availability.index');
        Route::get('/allAvailabilities', 'indexAll')->name('availability.indexAll');
    });

    Route::controller(ShiftController::class)->group(function () {
        Route::get('/schedule/{branch}/{week}', 'scheduleByWeek')->name('shifts.schedule');
        Route::get('/shifts', 'index')->name('shifts.index');
        Route::post('/shifts', 'store')->name('shifts.store');
        Route::put('/shifts/update', 'update')->name('shifts.update');
        Route::delete('/shifts/delete', 'destroy')->name('shifts.destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/employee/create', 'create')->name('employee.create');
        Route::post('/employee', 'store')->name('employee.store');
        Route::get('/employees', 'index')->name('employee.index');
        Route::get('/employee/{id}', 'edit')->name('employee.edit');
        Route::put('/employee/update/{id}', 'update')->name('employee.update');
        Route::delete('/employee/{id}', 'destroy')->name('employee.destroy');
    });

    Route::controller(BranchController::class)->group(function () {
        Route::get('/branches', 'index')->name('branches.index');
        Route::get('/branches/create', 'create')->name('branches.create');
        Route::post('/branches', 'store')->name('branches.store');
        Route::get('/branches/{id}/edit', 'edit')->name('branches.edit');
        Route::put('/branches/{id}', 'update')->name('branches.update');
        Route::delete('/branches/{id}', 'destroy')->name('branches.destroy');
    });

    Route::controller(TimeOffRequestController::class)->group(function () {
        Route::get('/timeoff', 'index')->name('timeoff.index');
    });
});

require __DIR__.'/auth.php';
