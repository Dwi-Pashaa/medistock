<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\CustomerController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\KategoriController;
use App\Http\Controllers\Pages\ProductController;
use App\Http\Controllers\Pages\PurchaseController;
use App\Http\Controllers\Pages\ReceivedController;
use App\Http\Controllers\Pages\ReportController;
use App\Http\Controllers\Pages\ReturController;
use App\Http\Controllers\Pages\RolesController;
use App\Http\Controllers\Pages\SupplierController;
use App\Http\Controllers\Pages\TransactionController;
use App\Http\Controllers\Pages\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('post.login');


Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('management-users')->group(function () {
        // ->middleware(['role:Dokter'])
        Route::prefix('roles')
            ->group(function () {
                Route::get('/', [RolesController::class, 'index'])->name('roles');
                Route::post('/store', [RolesController::class, 'store'])->name('roles.store');
                Route::get('/{id}/show', [RolesController::class, 'show'])->name('roles.show');
                Route::put('/{id}/update', [RolesController::class, 'update'])->name('roles.update');
                Route::delete('/{id}/destroy', [RolesController::class, 'destroy'])->name('roles.destroy');
                Route::get('/{id}/permission', [RolesController::class, 'permission'])->name('roles.permission');
                Route::put('/{id}/savePermission', [RolesController::class, 'savePermission'])->name('roles.savePermission');
            });

        Route::prefix('users')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('user');
                Route::get('/create', [UserController::class, 'create'])->name('user.create');
                Route::post('/store', [UserController::class, 'store'])->name('user.store');
                Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
                Route::put('/{id}/update', [UserController::class, 'update'])->name('user.update');
                Route::delete('/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
            });
    });

    Route::prefix('purchase')
        ->group(function () {
            Route::get('/', [PurchaseController::class, 'index'])->name('purchase');
            Route::get('/create', [PurchaseController::class, 'create'])->name('purchase.create');
            Route::post('/store', [PurchaseController::class, 'store'])->name('purchase.store');
            Route::get('/{id}/edit', [PurchaseController::class, 'edit'])->name('purchase.edit');
            Route::put('/{id}/update', [PurchaseController::class, 'update'])->name('purchase.update');
            Route::delete('/{id}/destroy', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
            Route::post('/getProduct', [PurchaseController::class, 'getProduct'])->name('purchase.getProduct');
            Route::get('/{code}/export', [PurchaseController::class, 'export'])->name('purchase.export');
        });

    Route::prefix('supplier')
        ->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('supplier');
            Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
            Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');
            Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
            Route::put('/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');
            Route::delete('/{id}/destroy', [SupplierController::class, 'destroy'])->name('supplier.destroy');
        });

    Route::prefix('customer')
        ->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customer');
            Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
            Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
            Route::put('/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
            Route::delete('/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer.destroy');
        });

    Route::prefix('product')
        ->group(function () {
            Route::prefix('kategori')->group(function () {
                Route::get('/', [KategoriController::class, 'index'])->name('kategori');
                Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store');
                Route::get('/{id}/show', [KategoriController::class, 'show'])->name('kategori.show');
                Route::put('/{id}/update', [KategoriController::class, 'update'])->name('kategori.update');
                Route::delete('/{id}/destroy', [KategoriController::class, 'destroy'])->name('kategori.destroy');
            });

            Route::prefix('list')
                ->group(function () {
                    Route::get('/', [ProductController::class, 'index'])->name('product');
                    Route::get('/create', [ProductController::class, 'create'])->name('product.create');
                    Route::post('/store', [ProductController::class, 'store'])->name('product.store');
                    Route::get('/{id}/print', [ProductController::class, 'show'])->name('product.print');
                    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
                    Route::put('/{id}/update', [ProductController::class, 'update'])->name('product.update');
                    Route::delete('/{id}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');
                });
        });

    Route::prefix('received')
        ->group(function () {
            Route::get('/', [ReceivedController::class, 'index'])->name('received');
            Route::post('store', [ReceivedController::class, 'store'])->name('received.store');
        });

    Route::prefix('return')
        ->group(function () {
            Route::get('/', [ReturController::class, 'index'])->name('retur');
            Route::get('/create', [ReturController::class, 'create'])->name('retur.create');
            Route::post('/getItem', [ReturController::class, 'getItem'])->name('retur.getItem');
            Route::post('/store', [ReturController::class, 'store'])->name('retur.store');
            Route::get('/{id}/edit', [ReturController::class, 'edit'])->name('retur.edit');
            Route::put('/{id}/update', [ReturController::class, 'update'])->name('retur.update');
            Route::delete('/{id}/destroy', [ReturController::class, 'destroy'])->name('retur.destroy');
        });

    Route::prefix('transaction')
        ->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('transaction');
            Route::get('/create', [TransactionController::class, 'create'])->name('transaction.create');
            Route::get('/getProduct', [TransactionController::class, 'getProduct'])->name('transaction.getProduct');
            Route::post('/store', [TransactionController::class, 'store'])->name('transaction.store');
            Route::get('/{id}/edit', [TransactionController::class, 'edit'])->name('transaction.edit');
            Route::put('/{id}/update', [TransactionController::class, 'update'])->name('transaction.update');
            Route::put('/{id}/updateStatus', [TransactionController::class, 'updateStatus'])->name('transaction.updateStatus');
            Route::delete('/{id}/destroy', [TransactionController::class, 'destroy'])->name('transaction.destroy');
            Route::get('/{invoice}/export', [TransactionController::class, 'export'])->name('transaction.export');
        });

    Route::prefix('report')->group(function () {
        Route::get('/purchase', [ReportController::class, 'index'])->name('report.purchase');
        Route::get('/purchase/{code}/detail', [ReportController::class, 'detailPurchase'])->name('report.detailPurchase');
        Route::get('/purchase/{code}/export', [ReportController::class, 'exportPurchase'])->name('report.purchase.export');
        Route::get('/retur', [ReportController::class, 'retur'])->name('report.retur');
        Route::get('/retur/export', [ReportController::class, 'exportRetur'])->name('report.export');
        Route::get('/sale', [ReportController::class, 'sale'])->name('report.sale');
        Route::get('/sale/export', [ReportController::class, 'exportSale'])->name('report.transaction.export');
    });
});
