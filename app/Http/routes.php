<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'customer','as'=>'customer.', 'middleware'=>'auth.checkrole:client'],function(){
		Route::get('order/create', ['as'=>'order.create','uses'=>'CheckoutController@create']);
		Route::post('order/store', ['as'=>'order.store','uses'=>'CheckoutController@store']);
		Route::get('order', ['as'=>'order.index','uses'=>'CheckoutController@index']);
});


Route::group(['prefix'=>'admin','as'=>'admin.', 'middleware'=>'auth.checkrole:admin'],function(){
	
	Route::group(['prefix'=>'categoria','as'=>'categories.'],function(){
		Route::get('', ['as'=>'index','uses'=>'CategoriesController@index']);
		Route::get('nova',['as'=>'create', 'uses'=>'CategoriesController@create']);
		Route::get('editar/{id}',['as'=>'edit', 'uses'=>'CategoriesController@edit']);
		Route::post('store',['as'=>'store', 'uses'=>'CategoriesController@store']);
		Route::post('update/{id}',['as'=>'update', 'uses'=>'CategoriesController@update']);
		Route::get('delete/{id}',['as'=>'delete', 'uses'=>'CategoriesController@delete']);
	});
	Route::group(['prefix'=>'produtos','as'=>'products.'],function(){
		Route::get('', ['as'=>'index','uses'=>'ProductsController@index']);
		Route::get('nova',['as'=>'create', 'uses'=>'ProductsController@create']);
		Route::get('editar/{id}',['as'=>'edit', 'uses'=>'ProductsController@edit']);
		Route::post('store',['as'=>'store', 'uses'=>'ProductsController@store']);
		Route::post('update/{id}',['as'=>'update', 'uses'=>'ProductsController@update']);
		Route::get('delete/{id}',['as'=>'delete', 'uses'=>'ProductsController@delete']);
	});
	Route::group(['prefix'=>'clientes','as'=>'clients.'],function(){
		Route::get('', ['as'=>'index','uses'=>'ClientsController@index']);
		Route::get('novo',['as'=>'create', 'uses'=>'ClientsController@create']);
		Route::get('editar/{id}',['as'=>'edit', 'uses'=>'ClientsController@edit']);
		Route::post('store',['as'=>'store', 'uses'=>'ClientsController@store']);
		Route::post('update/{id}',['as'=>'update', 'uses'=>'ClientsController@update']);
		Route::get('delete/{id}',['as'=>'delete', 'uses'=>'ClientsController@delete']);
	});
	Route::group(['prefix'=>'pedidos','as'=>'orders.'],function(){
		Route::get('', ['as'=>'index','uses'=>'OrdersController@index']);
		Route::get('novo',['as'=>'create', 'uses'=>'OrdersController@create']);
		Route::get('editar/{id}',['as'=>'edit', 'uses'=>'OrdersController@edit']);
		Route::post('store',['as'=>'store', 'uses'=>'OrdersController@store']);
		Route::post('update/{id}',['as'=>'update', 'uses'=>'OrdersController@update']);
		Route::get('delete/{id}',['as'=>'delete', 'uses'=>'OrdersController@delete']);
	});
	Route::group(['prefix'=>'cupoms','as'=>'cupoms.'],function(){
		Route::get('', ['as'=>'index','uses'=>'CupomsController@index']);
		Route::get('novo',['as'=>'create', 'uses'=>'CupomsController@create']);
		Route::get('editar/{id}',['as'=>'edit', 'uses'=>'CupomsController@edit']);
		Route::post('store',['as'=>'store', 'uses'=>'CupomsController@store']);
		Route::post('update/{id}',['as'=>'update', 'uses'=>'CupomsController@update']);
		Route::get('delete/{id}',['as'=>'delete', 'uses'=>'CupomsController@delete']);
	});

});

Route::group(['middleware'=>'cors'],function(){
	Route::post('oauth/access_token', function() {
	    return Response::json(Authorizer::issueAccessToken());
	});
Route::group(['prefix'=>'api','as'=>'api.', 'middleware'=>'oauth'],function(){

	Route::get('authenticated',['as'=>'authenticated', 'uses'=>'Api\ApiController@authenticated']);

		Route::group(['prefix'=>'client','middleware'=>'oauth.checkrole:client','as'=>'client.'],function(){
			Route::resource('order', 
				'Api\Client\ClientCheckoutController',
				['execpt'=>['create','edit','destroy']
		]);
			
		});
		Route::group(['prefix'=>'deliveryman','middleware'=>'oauth.checkrole:deliveryman','as'=>'deliveryman.'],function(){
			Route::resource('order', 
				'Api\Deliveryman\DeliverymanCheckoutController',
				['execpt'=>['create','edit','destroy']
			]);
			Route::patch('order/{id}/update-status',[
				'uses'=>'Api\Deliveryman\DeliverymanCheckoutController@updateStatus',
				'as'=>'orders.update_status']);
			
		});

		
	});
});