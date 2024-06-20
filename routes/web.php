<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Artisan;

Route::middleware(['guest','installed'])->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth','installed'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('permissions', [RoleController::class, 'permissions']);

    Route::get('change-lang/{lang}', [AppSettingController::class, 'changeLanguage'])->name('change.lang');
    Route::name('user.')->controller(UserController::class)->prefix('users')->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('update-profile', 'updateProfile')->name('update-profile');
        Route::match(['get', 'post'], 'change-password', 'changePassword')->name('change-password');
    });

    Route::middleware('authrized')->group(function () {
        Route::get('roles', [RoleController::class, 'roles']);
        Route::get('settings', [AppSettingController::class, 'settings'])->name('settings');
        Route::get('categories', [CategoryController::class, 'categories']);
        Route::get('units', [UnitController::class, 'units']);
        Route::get('suppliers', [SupplierController::class, 'suppliers']);
        Route::get('products', [ProductController::class, 'products'])->name('products');
        Route::get('customers', [CustomerController::class, 'customers']);

        Route::resource('role', RoleController::class);
        Route::resource('user', UserController::class)->except('show');

        Route::post('update-settings', [AppSettingController::class, 'updateSettings'])->name('update.settings');

        Route::resource('category', CategoryController::class);
        Route::resource('brand', BrandController::class);
        Route::resource('unit', UnitController::class);

        Route::resource('customer', CustomerController::class);
        Route::resource('supplier', SupplierController::class);

        Route::resource('product', ProductController::class);
        Route::resource('gateway', GatewayController::class);
        Route::post('product/{product}', [ProductController::class, 'update']);
        Route::resource('purchase', PurchaseController::class);
        Route::get('purchase/invoice/{id}', [PurchaseController::class, 'invoice'])->name('purchase.invoice');
        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::resource('sale', SaleController::class)->except('edit');
        Route::resource('prescription', PrescriptionController::class);
        Route::get('sale/invoice/{id}', [SaleController::class, 'invoice'])->name('sale.invoice');
        Route::get('database-backups', [DatabaseBackupController::class, 'index'])->name('database_backup.index');
        Route::get('database-backups/create', [DatabaseBackupController::class, 'create'])->name('database_backup.create');

        Route::prefix('report')
            ->controller(ReportController::class)
            ->group(function () {
                Route::get('sales', 'sales')->name('report.sales');
                Route::get('purchases', 'purchases')->name('report.purchases');
            });
    });

    Route::middleware('auth')->prefix('system')->group(function () {
        Route::get('clear', [AppSettingController::class,'restartSystem'])->name('system.restart');
    });

});
