<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDashboard2Controller;
use App\Http\Controllers\LeaveRequestsController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\BackLogController;
use App\Http\Controllers\RoomSchedulerController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PublicViewingController;
use App\Http\Controllers\MisOfficeInventoryController;


Route::get('/download-attachment/{id}', [LeaveRequestsController::class, 'downloadAttachment'])->name('download.attachment');

Route::get('/', function () {
    return view('accesspanel');
});

Route::get('/roomschedules', [PublicviewingController::class, 'viewBookings'])->name('home');
Route::post('/roomschedules', [RoomSchedulerController::class, 'publicStore'])->name('public.roomscheduler.store');
Route::get('/check-rooms', [PublicviewingController::class, 'checkRooms'])->name('public.check.rooms');

Route::get('/roomschedules2', [PublicviewingController::class, 'viewBookings2'])->name('home2');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/logout', function () {
//     session()->flush(); // clears all session data
//     return redirect('/login'); // redirect back to login page
// })->name('logout');

Route::get('/dashboard', function () {

    if (!session('logged_in') || session('admin_id') != 1) {
        return redirect('/login');
    }

    return app(\App\Http\Controllers\DashboardController::class)->index();
})->name('dashboard');

Route::get('/user-dashboard', function () {

    if (!session('logged_in') || session('admin_id') != 2) {
        return redirect('/login');
    }

    return app(\App\Http\Controllers\UserDashboardController::class)->index();
})->name('user-dashboard');

Route::get('/admin-dashboard', function () {

    if (!session('logged_in') || session('admin_id') != 3) {
        return redirect('/login');
    }

    return app(\App\Http\Controllers\AdminDashboardController::class)->index();
})->name('admin-dashboard');

Route::get('/admin-dashboard2', function () {

    if (!session('logged_in') || session('admin_id') != 4) {
        return redirect('/login');
    }

    return app(\App\Http\Controllers\AdminDashboard2Controller::class)->index();
})->name('admin-dashboard2');

// Leave Requests Routes (accessible by both Sister and Principal)
Route::get('/admin-leave-requests', [App\Http\Controllers\LeaveRequestsController::class, 'index'])
    ->name('leave-requests.index');
Route::get('/admin-leave-requests/filter/{status}', [App\Http\Controllers\LeaveRequestsController::class, 'filterByStatus'])
    ->name('leave-requests.filter');
Route::patch('/admin-leave-requests/{id}/status', [App\Http\Controllers\LeaveRequestsController::class, 'updateStatus'])
    ->name('leave-requests.update-status');


    
// Route::get('/viewbookings', [PublicViewingController::class, 'viewBookings'])->name('view.bookings');

// Route::get('/viewbookings', function () {
//     return view('viewbookings'); // public schedule viewer
// });

// Route::post('/login-redirect', function (Request $request) {
//     if ($request->user_type === 'admin') {
//         return redirect('/dashboard');
//     } elseif ($request->user_type === 'user') {
//         return redirect('/user-dashboard');
//     }
//     return redirect('/'); // fallback
// })->name('login.redirect');

// // admin dashboard
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// // user dashboard
// Route::get('/user-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
// Route::get('/inventoryitems', [InventoryItemController::class, 'inventoryitems'])->name('inventoryitems');

Route::resource('items', InventoryItemController::class)->names(['index' => 'inventoryitems',]);

Route::get('/officeitems/export', [MisOfficeInventoryController::class, 'export'])->name('officeitems.export');
Route::resource('officeitems', MisOfficeInventoryController::class)->names(['index' => 'officeinventory',]);


Route::get('/borrows/borrowitem', [BorrowController::class, 'borrowItem'])->name('borrows.borrowitem');
    Route::resource('borrows', BorrowController::class);

// Route::post('/requests/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
// Route::post('/requests/{id}/disapprove', [RequestController::class, 'disapprove'])->name('requests.disapprove');
// Route::post('/requests/{id}/disregard', [RequestController::class, 'disregard'])->name('requests.disregard');
Route::patch('/requests/{id}/status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');
Route::patch('/items/{id}/return', [InventoryItemController::class, 'returnItem'])->name('items.return');

Route::get('/backlogs', [BackLogController::class, 'backlogs'])->name('backlogs.index');

    Route::resource('roomscheduler', RoomSchedulerController::class)->only(['index', 'store']);
Route::get('/roomscheduler/logs', [RoomSchedulerController::class, 'showRoomLogs'])->name('roomscheduler.logs');
Route::get('/roomscheduler', [RoomSchedulerController::class, 'index'])->name('roomscheduler.index');
Route::delete('/roomscheduler/{id}', [RoomSchedulerController::class, 'destroy'])->name('roomscheduler.destroy');

Route::get('/roomscheduler/manageroom', [RoomSchedulerController::class, 'manageroom'])->name('rooms.management');
Route::get('/roomscheduler/manageroom', [RoomController::class, 'index'])->name('rooms.management');
    // Route::resource('rooms', RoomController::class);
Route::post('/manageroom/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::put('/roomscheduler/manageroom/{room}', [RoomController::class, 'update'])->name('rooms.update');
Route::delete('/manageroom/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

// User Room Scheduler
Route::get('/user-roomscheduler', [RoomSchedulerController::class, 'userRoomscheduler'])->name('user.roomscheduler');
// User Manage Room
Route::get('/user-manageroom', [RoomController::class, 'userManageroom'])->name('user.manageroom');

// User Room Scheduler routes
Route::post('/user-roomscheduler', [RoomSchedulerController::class, 'userStore'])->name('user.roomscheduler.store');
Route::delete('/user-roomscheduler/{id}', [RoomSchedulerController::class, 'userDestroy'])->name('user.roomscheduler.destroy');

// User Manage Room routes
Route::post('/user/rooms', [RoomController::class, 'userStore'])->name('user.rooms.store');
Route::put('/user/rooms/{room}', [RoomController::class, 'userUpdate'])->name('user.rooms.update');
Route::delete('/user/rooms/{room}', [RoomController::class, 'userDestroy'])->name('user.rooms.destroy');





