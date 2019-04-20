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

Route::prefix('/')->namespace('Member')->group(function () {
	// Auth::routes();
	Route::get('/', 'HomeController@index')->name('index');

	// Login
	Route::match(['get', 'post'], 'login', 	'Auth\LoginController@login')->name('login');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
	// Registration
	Route::match(['get', 'post'], 'register', 	'Auth\RegisterController@register')->name('register');
	//product
	Route::get('product/category/{name}', 	'ProductController@index')->name('member.product.index');
	//product detail
	Route::get('product/{id}', 			'ProductController@show')->name('member.product.show');

	Route::group(['middleware' => ['auth', 'member']], function(){

		Route::get('article', 					'ArticleController@index')->name('member.article.index');	

		Route::get('article/show/{id}', 		'ArticleController@show')->name('member.article.show');	
		// index
		Route::get('profile', 					'ProfileController@index')->name('member.profile.index');
		// setting
   		Route::post('profile/setting/{id}',		'ProfileController@setting')->name('member.profile.setting');
   		// change password
   		Route::post('profile/password/{id}', 	'ProfileController@password')->name('member.profile.password');
   		// change foto profile
		Route::post('profile/avatar/{id}', 		'ProfileController@avatar')->name('member.profile.avatar');

		//cart
		Route::match(['get', 'post'], 'cart', 	'CartController@index')->name('member.cart.index');
		Route::post('cart/add',					'CartController@store')->name('member.cart.store');
		Route::resource('cart',					'CartController', ['only' => [
						'update', 'destroy'
		]]);
		
		//request
		Route::match(['get', 'post'], 'request',	'RequestController@index')->name('member.request.index');
		Route::resource('request',						'RequestController', ['only' => [
						'show', 'destroy'
		]]);
		Route::post('request/bid',					'RequestController@bid')->name('member.request.bid');

		//transaction
		Route::match(['get', 'post'], 'transaction', 	'TransactionController@index')->name('member.transaction.index');
		Route::post('transaction/add', 					'TransactionController@store')->name('member.transaction.store');
		Route::resource('transaction',					'TransactionController', ['only' => [
						'update', 'show',
		]]);
		Route::post('transaction/cancel/{id}', 			'TransactionController@cancel')->name('member.transaction.cancel');

		// buy
		Route::get('buy', 								'BuyController@index')->name('member.buy.index');
		Route::match(['get', 'post'], 'history_buy', 	'BuyController@history')->name('member.buy.history');
		Route::post('buy/add', 							'BuyController@store')->name('member.buy.store');
		Route::resource('buy',							'BuyController', ['only' => [
						'show',
		]]);
	});
});

Route::prefix('owner')->namespace('Owner')->name('owner.')->group(function () {
	// Login
	Route::match(['get', 'post'], 'login', 'Auth\LoginController@login')->name('login');
	Route::post('logout',	'Auth\LoginController@logout')->name('logout');

	// Registration
	Route::match(['get', 'post'], 'register', 'Auth\RegisterController@register')->name('register');

	Route::group(['middleware' => ['auth:owner', 'owner']], function(){
		// home
		Route::get('/', 'HomeController@home')->name('home');
		// index
		Route::get('profile', 					'ProfileController@index')->name('profile.index');
	   	// setting
   		Route::post('profile/setting/{id}',		'ProfileController@setting')->name('profile.setting');
   		// change password
   		Route::post('profile/password/{id}', 	'ProfileController@password')->name('profile.password');
   		// change foto profile
		Route::post('profile/avatar/{id}', 		'ProfileController@avatar')->name('profile.avatar');

		// product
   		Route::match(['get', 'post'], 'product', 	'ProductController@index')->name('product.index');
   		Route::post('product/add',					'ProductController@store')->name('product.store');
		Route::resource('product',					'ProductController', ['only' => [
						'update', 'destroy', 'show'
		]]);

		// bank
   		Route::match(['get', 'post'], 'bank', 	'BankController@index')->name('bank.index');
   		Route::post('bank/add',					'BankController@store')->name('bank.store');
		Route::resource('bank',					'BankController', ['only' => [
						'update', 'destroy',
		]]);

		// delivery
   		Route::match(['get', 'post'], 'delivery', 	'DeliveryController@index')->name('delivery.index');
		Route::resource('delivery',					'DeliveryController', ['only' => [
						'update', 'show',
		]]);

		// refund
		Route::match(['get', 'post'], 'refund', 	'RefundController@index')->name('refund.index');

		// request
		Route::match(['get', 'post'], 'request', 	'RequestController@index')->name('request.index');
		Route::resource('request',					'RequestController', ['only' => [
						'show',
		]]);
		Route::get('on_progress', 						'RequestController@on_progress')->name('request.on_progress');
		Route::get('on_progress/detail/{id}',			'RequestController@detail')->name('request.detail');
		Route::post('on_progress/detail/confirmation', 	'RequestController@confirmation')->name('request.confirmation');

		// bid request
		Route::post('bid_request/bid', 'BidRequestController@bid')->name('bid_request.bid');

		// forum
		Route::get('forum', 		'ForumController@index')->name('forum.index');
		Route::post('forum/add', 	'ForumController@store')->name('forum.store');
		Route::resource('forum',	'ForumController', ['only' => [
						'show',
		]]);

		// forum_user
		Route::post('forum_user/add', 	'ForumUserController@store')->name('forum_user.store');
		Route::resource('forum_user',	'ForumUserController', ['only' => [
						'destroy',
		]]);

		// use_poin
		Route::match(['get', 'post'], 'use_poin', 	'UsePoinController@index')->name('use_poin.index');
		Route::post('use_poin/add', 'UsePoinController@store')->name('use_poin.store');
	});

});

Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
	// Login
	Route::match(['get', 'post'], 'login', 	'Auth\LoginController@login')->name('login');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');

	Route::group(['middleware' => ['auth:admin']], function(){
		// home
		Route::get('/', 'HomeController@home')->name('home');

		// Route::get('article/add', 			'ArticleController@add')->name('article.add');
		Route::match(['get', 'post'], 'article',	'ArticleController@index')->name('article.index');
		Route::match(['get', 'post'], 'article/add',	'ArticleController@store')->name('article.store');
		Route::match(['get', 'post'], 'article/edit/{id}',	'ArticleController@edit')->name('article.edit');
		Route::delete('article/destroy/{id}', 'ArticleController@destroy')->name('article.destroy');

		// index
		Route::get('profile', 				'ProfileController@index')->name('profile.index');
		// setting
   		Route::post('profile/setting/{id}',	'ProfileController@setting')->name('profile.setting');
   		// change password
   		Route::post('profile/password/{id}', 	'ProfileController@password')->name('profile.password');

   		// category
   		Route::match(['get', 'post'], 'category', 	'CategoryController@index')->name('category.index');
   		Route::post('category/add',					'CategoryController@store')->name('category.store');
		Route::resource('category',					'CategoryController', ['only' => [
						'update', 'destroy'
		]]);

		// bank
   		Route::match(['get', 'post'], 'bank', 	'BankController@index')->name('bank.index');
   		Route::post('bank/add',					'BankController@store')->name('bank.store');
		Route::resource('bank',					'BankController', ['only' => [
						'update', 'destroy',
		]]);

		// product
		Route::match(['get', 'post'], 'product', 	'ProductController@index')->name('product.index');
		Route::resource('product',					'ProductController', ['only' => [
						'show',
		]]);

		// management member
		Route::match(['get', 'post'], 'member', 	'MemberController@index')->name('member.index');
		Route::resource('member',					'MemberController', ['only' => [
						'show',
		]]);

		// management owner
		Route::match(['get', 'post'], 'owner', 	'OwnerController@index')->name('owner.index');
		Route::resource('owner',				'OwnerController', ['only' => [
						'show',
		]]);

		// transaction_sell
		Route::match(['get', 'post'], 'transaction_sell', 	'TransactionSellController@index')->name('transaction_sell.index');
		Route::resource('transaction_sell',					'TransactionSellController', ['only' => [
						'show',
		]]);
		Route::post('transaction_sell/approve/{id}', 		'TransactionSellController@approve')->name('transaction_sell.approve');
		Route::post('transaction_sell/reject/{id}', 		'TransactionSellController@reject')->name('transaction_sell.reject');
		Route::post('transaction_sell/finish/{id}', 		'TransactionSellController@finish')->name('transaction_sell.finish');

		// transaction_request
		Route::match(['get', 'post'], 'transaction_request', 	'TransactionRequestController@index')->name('transaction_request.index');
		Route::resource('transaction_request',					'TransactionRequestController', ['only' => [
						'show',
		]]);
		Route::post('transaction_request/approve/{id}', 	'TransactionRequestController@approve')->name('transaction_request.approve');
		Route::post('transaction_request/reject/{id}', 		'TransactionRequestController@reject')->name('transaction_request.reject');
		Route::post('transaction_request/finish/{id}', 		'TransactionRequestController@finish')->name('transaction_request.finish');

		// transaction_auction
		Route::match(['get', 'post'], 'transaction_auction', 	'TransactionAuctionController@index')->name('transaction_auction.index');
		Route::resource('transaction_auction',					'TransactionAuctionController', ['only' => [
						'show',
		]]);
		Route::post('transaction_auction/approve/{id}', 	'TransactionAuctionController@approve')->name('transaction_auction.approve');
		Route::post('transaction_auction/reject/{id}', 		'TransactionAuctionController@reject')->name('transaction_auction.reject');
		Route::post('transaction_auction/finish/{id}', 		'TransactionAuctionController@finish')->name('transaction_auction.finish');

		// refund_sell
		Route::match(['get', 'post'], 'refund_sell', 	'RefundSellController@index')->name('refund_sell.index');
		Route::resource('refund_sell',					'RefundSellController', ['only' => [
						'show',
		]]);
		Route::post('refund_sell/finish/{id}', 			'RefundSellController@finish')->name('refund_sell.finish');

		// refund_auction
		Route::match(['get', 'post'], 'refund_auction', 	'RefundAuctionController@index')->name('refund_auction.index');
		Route::resource('refund_auction',					'RefundAuctionController', ['only' => [
						'show',
		]]);
		Route::post('refund_auction/finish/{id}', 			'RefundAuctionController@finish')->name('refund_auction.finish');

		// refund_request
		Route::match(['get', 'post'], 'refund_request', 	'RefundRequestController@index')->name('refund_request.index');
		Route::resource('refund_request',					'RefundRequestController', ['only' => [
						'show',
		]]);
		Route::post('refund_request/finish/{id}', 			'RefundRequestController@finish')->name('refund_request.finish');

		// setting
		Route::match(['get', 'post'], 'setting', 	'SettingController@index')->name('setting.index');
		Route::resource('setting',					'SettingController', ['only' => [
						'update',
		]]);

		// buy
		Route::match(['get', 'post'], 'buy', 	'BuyController@index')->name('buy.index');
		Route::resource('buy',					'BuyController', ['only' => [
						'show',
		]]);
		Route::post('buy/approve/{id}', 		'BuyController@approve')->name('buy.approve');
		Route::post('buy/reject/{id}', 			'BuyController@reject')->name('buy.reject');
	});
});
