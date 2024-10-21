<?php

use App\Http\Controllers\AssetController;
// use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UsingController;
use App\Models\Asset;
use PhpParser\Node\Stmt\Return_;

use function Deployer\warning;

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

Route::get('/dashboard', function () {
    return view('dashboard.user_index');
})->middleware('auth');


//AUTENTIKASI
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('action_login', [LoginController::class, 'action_login'])->name('action_login')->middleware('limiter');
Route::get('action_logout', [LoginController::class, 'action_logout'])->name('action_logout')->middleware('auth');

Route::get('user/forget/password', function () {
    return view('users.forgetPassword');
});
Route::post('request_password_reset', [UserController::class, 'requestPasswordReset'])->name('request_password_reset');
Route::get('/change_password', function(){
    return view('users.updatePassword');
});
Route::put('resetPassword', [UserController::class, 'resetPassword'])->name('resetPassword');

//DASHBOARD
Route::get('/dashboard', [HomeController::class, 'index'])->name('home.index')->middleware('auth');
Route::get('/api/events', [HomeController::class, 'getEvents'])->middleware('auth');

//ASSET
Route::get('asset/create', [AssetController::class, 'create'])->middleware(['auth', 'is_admin']);
Route::resource('asset', AssetController::class)->middleware('auth');
Route::get('/search', [AssetController::class, 'search'])->middleware('auth');
Route::get('asset-information/', [AssetController::class, 'information'])->name('asset.information');
Route::get('update_status/', [AssetController::class, 'update_status'])->name('asset.status');

//LOAN
Route::get('/loans', [LoanController::class, 'index'])->middleware('auth');
Route::put('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve')->middleware('auth');
Route::resource('loans', LoanController::class)->middleware('auth');
Route::get('/search', [LoanController::class, 'search'])->middleware('auth');
Route::get('loans-information', [LoanController::class, 'information'])->name('loans.information')->middleware('auth');
Route::post('loan-recap', [DocumentController::class, 'loanRecap'])->name('loan.recap')->middleware('auth');

//USER
Route::get('/users/admins', [UserController::class, 'admin'])->middleware('auth')->name('admin.index');
Route::get('/users/students', [UserController::class, 'student'])->middleware('auth')->name('student.index');
Route::get('/user/{user_id}/myacount', [UserController::class, 'show'])->name('user.view')->middleware('auth');
Route::put('/users/{user_id}/updateacount', [UserController::class, 'updateAcount'])->name('user.updateAcount')->middleware('auth');
Route::resource('user', UserController::class)->middleware('auth');
Route::put('user_change_password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::get('user-information', [UserController::class, 'information'])->name('user.information')->middleware('auth');

//LETERS
Route::get('/loan/letter/{loan_id}', [DocumentController::class, 'letter'])->name('loans.letter')->middleware('auth');
Route::get('asset/label/{asset_id}', [DocumentController::class, 'label'])->name('asset.label')->middleware('auth');
Route::post('asset-recap', [DocumentController::class, 'assetRecap'])->name('asset.recap')->middleware('auth');

// RECAP
Route::get('recap-data', [DocumentController::class, 'dataRecap'])->name('data.recap')->middleware('auth');

//USING
Route::get('loan/{loan_id}/using', [UsingController::class, 'using'])->name('using.form')->middleware('auth');
Route::post('loan/{loan_id}/using_evidence', [UsingController::class, 'using_evidence'])->name('using.evidence')->middleware('auth');

//RETURN
Route::get('loan/{loan_id}/returning', [ReturnController::class, 'returning'])->name('returning.form')->middleware('auth');
Route::post('loan/{loan_id}/return_evidence', [ReturnController::class, 'return_evidence'])->name('returning.evidence')->middleware('auth');

//Mail
Route::get('send-mail', [MailController::class, 'index']);
Route::get('/send-message', [MessageController::class, 'sendMessage']);

//Category
Route::get('asset-category-create', function () {
    return view('categories.createAssetCategory');
})->name('asset_categories.create')->middleware('auth');

Route::post('asset-category-oncreate', [AssetController::class, 'category_store'])->name('asset_categories.store')->middleware('auth');
Route::delete('asset_category/{category_id}', [AssetController::class, 'category_destroy'])->name('asset_category.destroy')->middleware('auth');
Route::get('asset_category/edit/{category_id}', [AssetController::class, 'category_edit'])->name('asset_category.edit')->middleware('auth');
Route::put('asset_category/update/{category_id}', [AssetController::class, 'category_update'])->name('asset_category.update')->middleware('auth');

Route::get('user-category-create', function () {
    return view('categories.createUserCategory');
})->name('user_categories.create')->middleware('auth');

Route::post('user-category-oncreate', [UserController::class, 'category_store'])->name('user_categories.store')->middleware('auth');
Route::delete('user_category/{category_id}', [UserController::class, 'category_destroy'])->name('user_category.destroy')->middleware('auth');
Route::get('user_category/edit/{category_id}', [UserController::class, 'category_edit'])->name('user_category.edit')->middleware('auth');
Route::put('user_category/update/{category_id}', [UserController::class, 'category_update'])->name('user_category.update')->middleware('auth');


//New Feature
Route::post('recap-data/asset_csv/', [AssetController::class, 'importAssetCsv'])->name('data.assetCSV')->middleware('auth');
Route::post('recap-data/loan_csv/', [LoanController::class, 'importLoanCsv'])->name('data.loanCSV')->middleware('auth');
