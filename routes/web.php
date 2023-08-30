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

Route::view('/login', 'auth.login')->middleware('loggedin')->name('login');

Route::post('login', 'Master\AuthController@login');


Route::group(['middleware' => ['auth.login']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('logout', 'Master\AuthController@logout')->name('logout');
    Route::get('change_password', 'Master\AuthController@show_change_password')->name('change_password.index');
    Route::post('change_password', 'Master\AuthController@change_password')->name('change_password.update');
    Route::get('mark_all_read', 'Master\NotificationController@markAllRead')->name('notification.read_all');

    // EXPORT EXCEL 
    Route::post('export/excel', 'Utility\ExcelController@export')->name('export.excel');
    Route::post('export/excel/sheets', 'Utility\ExcelSheetsController@export')->name('export.excel.sheet');

    // Search Route
    Route::post('user/search', 'Master\UserController@search')->name('user.search');
    Route::post('product/search', 'Master\ProductController@search')->name('product.search');
    Route::post('order/search', 'Master\OrderController@searchOrder')->name('order.search');
    Route::post('groupbuy/search', 'Master\OrderController@searchGb')->name('order.searchGB');
    Route::post('affiliate/search', 'Master\AffiliateController@search')->name('affiliate.search');

    //Merge
    Route::post('groupbuy/merge', 'Master\OrderController@mergeGb')->name('order.mergeGB');

    // Test
    // Route::get('pdf', 'Utility\PdfController@index');
    // Route::get('test', 'Utility\TestController@test');
    // Route::post('test', 'Utility\TestController@tepos');
});
Route::group(['middleware' => ['auth.login', 'auth.menu']], function () {
    Route::group(['menu' => 'admin'], function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('role', 'Master\AdminController@roleList')->name('role.index');
            Route::post('role', 'Master\AdminController@roleStore')->name('role.store');
            Route::get('role/{id}', 'Master\AdminController@roleEdit')->name('role.edit');
            Route::post('role/{id}', 'Master\AdminController@roleUpdate')->name('role.update');
            Route::post('reset_password/{id}', 'Master\AdminController@reset_password')->name('reset');
        });
        Route::resource('admin', 'Master\AdminController')->except(['show']);
    });

    Route::group(['menu' => 'category'], function () {
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('sub/{id}', 'Master\CategoryController@subShow')->name('sub.show');
            Route::post('sub', 'Master\CategoryController@subStore')->name('sub.store');
            Route::put('sub/{id}', 'Master\CategoryController@subUpdate')->name('sub.update');
            Route::delete('sub/{id}', 'Master\CategoryController@subDestroy')->name('sub.destroy');
            Route::get('getDtRowData', 'Master\CategoryController@getDtRowData')->name('dtRow');
        });
        Route::resource('category', 'Master\CategoryController')->except(['show']);
    });

    Route::group(['menu' => 'brand'], function () {
        Route::get('brand/check', 'Master\BrandController@checkName')->name('brand.checkName');
        Route::get('brand/getDtRowData', 'Master\BrandController@getDtRowData')->name('brand.dtRow');
        Route::resource('brand', 'Master\BrandController')->except(['show']);
    });

    Route::group(['menu' => 'product'], function () {
        Route::prefix('product')->name('product.')->group(function () {
            Route::post('variant', 'Master\ProductController@variantStore')->name('variant.store');
            Route::put('variant/{id}', 'Master\ProductController@variantUpdate')->name('variant.update');
            Route::delete('variant/{id}', 'Master\ProductController@variantDestroy')->name('variant.destroy');
            Route::post('variant/publish', 'Master\ProductController@variantPublish')->name('variant.publish');
            Route::post('gallery', 'Master\ProductController@galleryStore')->name('gallery.store');
            Route::post('gallery/{id}', 'Master\ProductController@galleryMain')->name('gallery.main');
            Route::put('gallery/{id}', 'Master\ProductController@galleryUpdate')->name('gallery.update');
            Route::delete('gallery/{id}', 'Master\ProductController@galleryDestroy')->name('gallery.destroy');
            Route::get('getDtRowData', 'Master\ProductController@getDtRowData')->name('dtRow');
            Route::get('digital', 'Master\ProductController@digitalIndex')->name('digital.index');
            Route::get('digital/create', 'Master\ProductController@digitalCreate')->name('digital.create');
            Route::post('digital', 'Master\ProductController@digitalStore')->name('digital.store');
            Route::get('digital/{product}/edit', 'Master\ProductController@digitalEdit')->name('digital.edit');
            Route::put('digital/{product}', 'Master\ProductController@digitalUpdate')->name('digital.update');
            Route::get('digital/getDtRowData', 'Master\ProductController@getDtRowDataDigital')->name('digital.dtRow');
            Route::post('bundle', 'Master\ProductController@digitalBundleStore')->name('bundle.store');
            Route::put('bundle/{id}', 'Master\ProductController@digitalBundleUpdate')->name('bundle.update');
        });
        Route::resource('product', 'Master\ProductController')->except(['show']);
    });

    Route::group(['menu' => 'banner'], function () {
        Route::get('banner/getDtRowData', 'Master\BannerController@getDtRowData')->name('banner.dtRow');
        Route::resource('banner', 'Master\BannerController')->except(['create', 'show', 'edit']);
    });

    Route::group(['menu' => 'content'], function () {
        Route::prefix('content')->name('content.')->group(function () {
            Route::get('post', 'Master\ContentController@contentPost')->name('post');
            Route::get('review/{id}', 'Master\ContentController@contentReview')->name('review');
            Route::post('review', 'Master\ContentController@contentCurate')->name('curate');
            Route::post('product/assign', 'Master\ContentController@assignProduct')->name('product.assign');
            Route::get('getDtRowData', 'Master\ContentController@getDtRowData')->name('dtRow');
        });
    });

    Route::group(['menu' => 'article'], function () {
        Route::get('article/getDtRowData', 'Master\ArticleController@getDtRowData')->name('article.dtRow');
        Route::resource('article', 'Master\ArticleController')->except(['show']);
    });

    Route::group(['menu' => 'user'], function () {
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('getDtRowData', 'Master\UserController@getDtRowData')->name('dtRow');
            Route::get('review/load/{id}', 'Master\UserController@loadReview')->name('review');
        });
        Route::resource('user', 'Master\UserController')->except(['create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::group(['menu' => 'point'], function () {
        Route::prefix('point')->name('point.')->group(function () {
            Route::get('getDtRowData', 'Master\PointController@getDtRowData')->name('dtRow');
            Route::get('multiply/{status}', 'Master\PointController@pointMultiply')->name('multiply');
            Route::post('adjust', 'Master\PointController@pointAdjust')->name('adjust');
        });
        Route::resource('point', 'Master\PointController')->except(['create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::group(['menu' => 'agent'], function () {
        Route::prefix('agent')->name('agent.')->group(function () {
            Route::get('getDtRowData', 'Master\AgentController@getDtRowData')->name('dtRow');
        });
        Route::resource('agent', 'Master\AgentController')->except(['show']);
    });

    Route::group(['menu' => 'collection'], function () {
        Route::prefix('collection')->name('collection.')->group(function () {
            Route::post('product/{mode}', 'Master\CollectionController@itemAssignment')->name('assign');
            Route::get('publish/{collection_id}', 'Master\CollectionController@publish')->name('publish');
            Route::get('getDtRowData', 'Master\CollectionController@getDtRowData')->name('dtRow');
            Route::get('getDtItemRowData/{collection_id}', 'Master\CollectionController@getDtItemRowData')->name('item.dtRow');
        });
        Route::resource('collection', 'Master\CollectionController')->except(['show']);
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::group(['menu' => 'order_verification'], function () {
            Route::get('verification', 'Master\OrderController@verificationIndex')->name('verification');
            Route::post('verify/payment', 'Master\OrderController@verifyPayment')->name('verify.payment');
            Route::get('getDtRowVerificationData', 'Master\OrderController@getDtRowVerificationData')->name('verification.dtRow');
            Route::get('verification/{order}', 'Master\OrderController@orderVerificationDetails')->name('verification.details');
        });
        Route::group(['menu' => 'order_delivery'], function () {
            Route::get('delivery', 'Master\OrderController@deliveryIndex')->name('delivery');
            Route::post('pickup', 'Master\OrderController@requestPickup')->name('delivery.pickup');
            Route::post('pickup/bulk/create', 'Master\OrderController@requestPickupCreate')->name('delivery.pickup.create');
            Route::post('pickup/bulk', 'Master\OrderController@requestPickupBulk')->name('delivery.pickup.bulk');
            Route::post('verify/delivery', 'Master\OrderController@verifyDelivery')->name('verify.delivery');
            Route::post('verify/delivery/bulk', 'Master\OrderController@verifyDeliveryBulk')->name('verify.delivery.bulk');
            Route::get('getDtRowDeliveryData', 'Master\OrderController@getDtRowDeliveryData')->name('delivery.dtRow');
            Route::get('delivery/{order}', 'Master\OrderController@orderDeliveryDetails')->name('delivery.details');
            Route::get('download/receipt', 'Master\OrderController@downloadReceipt')->name('download.receipt');
        });
        Route::group(['menu' => 'groupbuy'], function () {
            Route::get('groupbuy', 'Master\OrderController@groupBuyIndex')->name('groupbuy');
            Route::post('verify/groupbuy', 'Master\OrderController@verifyGroupBuy')->name('verify.groupbuy');
            Route::get('getDtRowGroupBuyData', 'Master\OrderController@getDtRowGroupBuyData')->name('groupbuy.dtRow');
            Route::get('groupbuy/{cg_id}', 'Master\OrderController@orderGroupBuyDetails')->name('groupbuy.details');
        });
    });

    Route::group(['menu' => 'reward'], function () {
        Route::prefix('reward')->name('reward.')->group(function () {
            Route::get('getDtRowData', 'Master\RewardController@getDtRowData')->name('dtRow');
        });
        Route::resource('reward', 'Master\RewardController')->except(['show']);
    });

    Route::group(['menu' => 'voucher'], function () {
        Route::prefix('voucher')->name('voucher.')->group(function () {
            Route::post('publish', 'Master\VoucherController@publish')->name('publish');
            Route::get('getDtRowData', 'Master\VoucherController@getDtRowData')->name('dtRow');
        });
        Route::resource('voucher', 'Master\VoucherController');
    });

    Route::group(['menu' => 'affiliate'], function () {
        Route::prefix('affiliate')->name('affiliate.')->group(function () {
            Route::prefix('article')->name('article.')->group(function () {
                Route::get('', 'Master\AffiliateController@article')->name('list');
                Route::get('getDtRowData', 'Master\AffiliateController@getDtRowArticleData')->name('dtRow');
                Route::get('{id}', 'Master\AffiliateController@articleDetails')->name('details');
                Route::post('curate', 'Master\AffiliateController@articleCurate')->name('curate');
            });
            Route::prefix('bank')->name('bank.')->group(function () {
                Route::get('', 'Master\AffiliateController@bank')->name('list');
                Route::get('getDtRowData', 'Master\AffiliateController@getDtRowBankData')->name('dtRow');
                Route::get('{id}', 'Master\AffiliateController@bankDetails')->name('details');
            });
            Route::prefix('withdraw')->name('withdraw.')->group(function () {
                Route::get('', 'Master\AffiliateController@withdraw')->name('list');
                Route::get('getDtRowData', 'Master\AffiliateController@getDtRowWithdrawData')->name('dtRow');
                Route::get('{id}', 'Master\AffiliateController@withdrawDetails')->name('details');
                Route::post('approval', 'Master\AffiliateController@withdrawApproval')->name('approval');
            });
            Route::prefix('credit')->name('credit.')->group(function () {
                Route::get('', 'Master\AffiliateController@credit')->name('list');
                Route::get('getDtRowData', 'Master\AffiliateController@getDtRowCreditData')->name('dtRow');
                Route::post('adjust', 'Master\AffiliateController@creditAdjust')->name('adjust');
            });
        });
    });
});
