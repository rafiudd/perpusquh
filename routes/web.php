<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('', [App\Http\Controllers\CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [App\Http\Controllers\CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::post('logout', [App\Http\Controllers\CustomAuthController::class, 'signOut'])->name('logout');

// admin route
// order management
Route::get('/dashboard', [App\Http\Controllers\DashboardAdminController::class, 'index'])->middleware(['role:admin|karyawan']);
// Route::get('dashboard/404', [App\Http\Controllers\CustomAuthController::class, 'notFoundAdmin'])->name('404admin');
// Route::get('/dashboard/book-management', [App\Http\Controllers\DashboardAdminController::class, 'orders'])->name('book-management')->middleware(['role:admin|karyawan']);
// Route::get('/dashboard/book-management/search', [App\Http\Controllers\DashboardAdminController::class, 'searchOrder'])->name('book-management')->middleware(['role:admin|karyawan']);
// Route::get('/dashboard/book-management/approve/{order_id}', [App\Http\Controllers\DashboardAdminController::class, 'approveOrder'])->name('book-approve')->middleware(['role:admin|karyawan']);
// Route::get('/dashboard/book-management/reject/{order_id}', [App\Http\Controllers\DashboardAdminController::class, 'rejectOrder'])->name('book-reject')->middleware(['role:admin|karyawan']);
// Route::get('/dashboard/book-management/delete/{order_id}', [App\Http\Controllers\DashboardAdminController::class, 'deleteOrder'])->name('delete-approve')->middleware(['role:admin|karyawan']);
// Route::get('/dashboard/book-management/taken/{order_id}', [App\Http\Controllers\DashboardAdminController::class, 'takenOrder'])->name('book-taken')->middleware(['role:admin|karyawan']);
// Route::get('/dashboard/book-management/{order_id}', [App\Http\Controllers\DashboardAdminController::class, 'getDetailOrder'])->name('book-management')->middleware(['role:admin|karyawan']);

// book management
Route::get('/dashboard/book-management', [App\Http\Controllers\BookController::class, 'index'])->name('book-management')->middleware(['role:admin']);
Route::get('/dashboard/book-management/create', [App\Http\Controllers\DashboardAdminController::class, 'createBook'])->name('book-management')->middleware(['role:admin|karyawan']);
Route::post('/dashboard/book-management/store', [App\Http\Controllers\DashboardAdminController::class, 'storeBook'])->name('book-store')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/book-management/delete/{product_id}', [App\Http\Controllers\DashboardAdminController::class, 'deleteBook'])->name('book-delete')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/book-management/search', [App\Http\Controllers\DashboardAdminController::class, 'searchBook'])->name('book-management')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/book-management/{product_id}', [App\Http\Controllers\DashboardAdminController::class, 'editBook'])->name('book-management')->middleware(['role:admin|karyawan']);
Route::put('/dashboard/book-management/update/{product_id}', [App\Http\Controllers\DashboardAdminController::class, 'updateBook'])->name('book-management')->middleware(['role:admin|karyawan']);

// user management
Route::get('/dashboard/user-management', [App\Http\Controllers\DashboardAdminController::class, 'getUsers'])->name('user-management')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/user-management/create', [App\Http\Controllers\DashboardAdminController::class, 'createUser'])->name('user-management')->middleware(['role:admin|karyawan']);
Route::post('/dashboard/user-management/store', [App\Http\Controllers\DashboardAdminController::class, 'storeUser'])->name('user-store')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/user-management/delete/{user_id}', [App\Http\Controllers\DashboardAdminController::class, 'deleteUser'])->name('user-delete')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/user-management/search', [App\Http\Controllers\DashboardAdminController::class, 'searchUser'])->name('user-management')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/user-management/{user_id}', [App\Http\Controllers\DashboardAdminController::class, 'editUser'])->name('user-management')->middleware(['role:admin|karyawan']);
Route::put('/dashboard/user-management/update/{user_id}', [App\Http\Controllers\DashboardAdminController::class, 'updateUser'])->name('user-management')->middleware(['role:admin|karyawan']);

// profile
Route::get('/dashboard/profile', [App\Http\Controllers\ProfileController::class, 'admin'])->name('profile-admin')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/profile/edit', [App\Http\Controllers\ProfileController::class, 'editProfile'])->name('profile-admin')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/profile/update/{user_id}', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile-admin')->middleware(['role:admin|karyawan']);

// order history
Route::get('/dashboard/order-history', [App\Http\Controllers\DashboardAdminController::class, 'orderHistory'])->name('order-history')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/order-history/search', [App\Http\Controllers\DashboardAdminController::class, 'searchHistory'])->name('order-history')->middleware(['role:admin|karyawan']);
Route::get('/dashboard/order-history/{order_id}', [App\Http\Controllers\DashboardAdminController::class, 'getDetailHistory'])->name('order-history')->middleware(['role:admin|karyawan']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/generate-pdf', [App\Http\Controllers\OrderController::class, 'generatePDF']);
