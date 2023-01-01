<?php

use App\Http\Controllers\Controller;
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

Route::get('/', [Controller::class, 'viewHome'])->name('home');
Route::get('/home', [Controller::class, 'viewHome']);

Route::get('/register', [Controller::class, 'viewRegister'])->name('register');
Route::post('/register', [Controller::class, 'runRegister']);
Route::get('/login', [Controller::class, 'viewLogin'])->name('login');
Route::post('/login', [Controller::class, 'runLogin']);
Route::get('/editProfile', [Controller::class, 'viewEdit'])->name('viewEdit');
Route::put('/editProfile', [Controller::class, 'runEditProfile'])->name('runEditProfile');
Route::get('/changePassword', [Controller::class, 'viewChange'])->name('viewChange');
Route::put('/changePassword', [Controller::class, 'runChangePassword'])->name('runChangePassword');
Route::get('/logout', [Controller::class, 'runLogout'])->name('logout');

Route::get('/showProduct', [Controller::class, 'viewProducts'])->name('viewProducts');
Route::get('/viewItem', [Controller::class, 'viewManageItem'])->middleware('authenticaterole:admin')->name('viewItem');
Route::get('/addItem', [Controller::class, 'viewAddItem'])->name('addItem')->middleware('authenticaterole:admin');
Route::post('/addItem', [Controller::class, 'runAddItem']);
Route::get('/products/{product:id}', [Controller::class, 'viewProductDetail'])->name('productDetail');
Route::get('/updateItem/{product:id}', [Controller::class, 'viewUpdateItem'])->name('updateItem')->middleware('authenticaterole:admin');
Route::put('/updateItem/{product:id}', [Controller::class, 'runUpdateItem']);
Route::delete('/deleteItem/{product:id}', [Controller::class, 'deleteItem']);

Route::get('/cartList', [Controller::class, 'viewCart'])->name('cartList');
Route::post('/addcart', [Controller::class, 'runAddCart']);
Route::get('/updateCartqty/{product:id}', [Controller::class, 'viewUpdateCart']);
Route::put('/updateCartItem', [Controller::class, 'runUpdateCartqty']);
Route::post('/deleteCartItem', [Controller::class, 'runDeleteCartItem']);

Route::get('/transactionHistory', [Controller::class, 'viewTransaction'])->middleware('authenticaterole:customer')->name('transactionHistory');
Route::post('/checkout', [Controller::class, 'runCheckout']);
