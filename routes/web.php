<?php

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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::get('sales/create', function () {
	    	return view('cashier.sales.create');
	})->name('sales.create');
	Route::get('purchases/create', function () {
	    	return view('cashier.purchases.create');
	})->name('purchases.create');
});

Route::group(['middleware' => ['auth','role:admin'], 'prefix' => 'admin'], function () {
    Route::get('suppliers', function () {
    	return view('admin.suppliers.index');
	})->name('suppliers.index');
	Route::get('suppliers/create', function () {
    	return view('admin.suppliers.create');
	})->name('suppliers.create');

	Route::get('departments', function () {
    	return view('admin.departments.index');
	})->name('departments.index');
	Route::get('departments/create', function () {
    	return view('admin.departments.create');
	})->name('departments.create');

    Route::get('items', function () {
    	return view('admin.items.index');
	})->name('items.index');
	Route::get('items/create', function () {
    	return view('admin.items.create');
	})->name('items.create');

	Route::get('sales', function () {
    	return view('admin.sales.index');
	})->name('sales.index');
	Route::get('salesinvoices', function () {
    	return view('admin.salesinvoices.index');
	})->name('salesinvoices.index');

	Route::get('purchases', function () {
    	return view('admin.purchases.index');
	})->name('purchases.index');
	Route::get('purchasesinvoices', function () {
    	return view('admin.purchasesinvoices.index');
	})->name('purchasesinvoices.index');

	Route::get('reports', function () {
    	return view('admin.reports.index');
	})->name('reports.index');
	Route::get('reportspurchase', function () {
    	return view('admin.reports.purchase');
	})->name('reports.purchase');

	Route::get('locations', function () {
    	return view('admin.locations.index');
	})->name('locations.index');
	Route::get('locations/create', function () {
    	return view('admin.locations.create');
	})->name('locations.create');
});

Route::group(['middleware' => ['auth'],'prefix' => 'api/v1', 'as' => 'api.v1.'], function () {
	Route::get('sales/func/invoice', 'Api\V1\SalesController@invoice');
	Route::get('purchases/func/invoice', 'Api\V1\PurchasesController@invoice');
	Route::get('salesinvoices/func/invoice', 'Api\V1\SalesInvoicesController@invoice');
	Route::get('salesinvoices/func/newid', 'Api\V1\SalesInvoicesController@newId');
	Route::get('salesinvoices/func/currentUser', 'Api\V1\SalesInvoicesController@currentUser');
	Route::get('purchasesinvoices/func/invoice', 'Api\V1\PurchasesInvoicesController@invoice');
	Route::get('purchasesinvoices/func/newid', 'Api\V1\PurchasesInvoicesController@newId');
	Route::get('purchasesinvoices/func/currentUser', 'Api\V1\PurchasesInvoicesController@currentUser');
	Route::get('suppliers/search/{searchVar}', 'Api\V1\SuppliersController@search');
	Route::get('items/search/{searchVar}', 'Api\V1\ItemsController@search');
	Route::get('items/supplierBasedSearch/{supplier}', 'Api\V1\ItemsController@supplierBasedSearch');
	Route::get('items/supplierBasedItemSearch/{supplier}/{item_name}', 'Api\V1\ItemsController@supplierBasedItemSearch');
	Route::resource('purchasesinvoices', 'Api\V1\PurchasesInvoicesController', ['except' => ['create', 'edit']]);
	Route::resource('sales', 'Api\V1\SalesController', ['except' => ['create', 'edit']]);
	Route::resource('salesinvoices', 'Api\V1\SalesInvoicesController', ['except' => ['create', 'edit']]);
	Route::resource('purchases', 'Api\V1\PurchasesController', ['except' => ['create', 'edit']]);
});

Route::group(['middleware' => ['auth','role:admin'],'prefix' => 'api/v1', 'as' => 'api.v1.'], function () {
	Route::resource('suppliers', 'Api\V1\SuppliersController', ['except' => ['create', 'edit']]);
		Route::get('suppliers/code/{code}', 'Api\V1\SuppliersController@code');
	Route::resource('departments', 'Api\V1\DepartmentsController', ['except' => ['create', 'edit']]);
		Route::get('departments/code/{code}', 'Api\V1\DepartmentsController@code');
	Route::resource('items', 'Api\V1\ItemsController', ['except' => ['create', 'edit']]);
		Route::get('items/{id}/supplier', 'Api\V1\ItemsController@supplier');
		Route::get('items/{id}/department', 'Api\V1\ItemsController@department');
		Route::get('items/{id}/stocks', 'Api\V1\ItemsController@stock');
		Route::get('items/func/recount', 'Api\V1\ItemsController@recount');
		Route::get('items/func/report', 'Api\V1\ItemsController@report');
		Route::get('items/func/reportpurchase', 'Api\V1\ItemsController@reportpurchase');
		Route::get('sales/func/profit', 'Api\V1\SalesController@profit');
		Route::get('sales/func/recount', 'Api\V1\SalesController@recount');
		Route::get('sales/func/chart', 'Api\V1\SalesController@chart');
		Route::get('salesinvoices/func/recount', 'Api\V1\SalesInvoicesController@recount');
		Route::get('salesinvoices/func/income', 'Api\V1\SalesInvoicesController@income');
		Route::get('salesinvoices/func/chart', 'Api\V1\SalesInvoicesController@chart');
		Route::get('purchases/func/profit', 'Api\V1\PurchasesController@profit');
		Route::get('purchases/func/recount', 'Api\V1\PurchasesController@recount');
		Route::get('purchasesinvoices/func/recount', 'Api\V1\PurchasesInvoicesController@recount');
		Route::get('purchasesinvoices/func/outcome', 'Api\V1\PurchasesInvoicesController@outcome');
		Route::get('purchasesinvoices/func/chart', 'Api\V1\PurchasesInvoicesController@chart');
		Route::get('purchasesinvoices/func/totaldebt', 'Api\V1\PurchasesInvoicesController@totalDebt');
		Route::get('purchasesinvoices/func/soondebt', 'Api\V1\PurchasesInvoicesController@soonDebt');
	Route::resource('locations', 'Api\V1\LocationsController', ['except' => ['create', 'edit']]);
		Route::get('locations/search/{name}', 'Api\V1\LocationsController@search');
});

