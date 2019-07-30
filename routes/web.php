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

use Illuminate\Support\Facades\Route;

Route::get('/', 'SearchController@index');
Route::post('/', 'SearchController@query');

Route::get('dashboard', 'DashboardController@index');

Route::get('server', 'ServerController@index')->name('serverIndex');
Route::post('server', 'ServerController@store');
Route::get('server/json', 'ServerController@json');
Route::get('server/refresh', 'ServerController@refresh')->name('serverRefresh');
Route::get('server/create', 'ServerController@create');
Route::get('server/{id}', 'ServerController@show');
Route::patch('server/{id}', 'ServerController@update');
Route::get('server/{id}/edit', 'ServerController@edit');
Route::delete('server/{id}/destroy', 'ServerController@destroy');

Route::get('pool', 'PoolController@index');
Route::post('pool', 'PoolController@store');
Route::get('pool/json', 'PoolController@json');
Route::get('pool/create', 'PoolController@create');
Route::get('pool/{id}', 'PoolController@show');
Route::patch('pool/{id}', 'PoolController@update');
Route::get('pool/{id}/edit', 'PoolController@edit');
Route::delete('pool/{id}/destroy', 'PoolController@destroy');

Route::get('crypto', 'CryptoController@index');
Route::post('crypto', 'CryptoController@store');
Route::get('crypto/json', 'CryptoController@json');
Route::get('crypto/create', 'CryptoController@create');
Route::get('crypto/{id}', 'CryptoController@show');
Route::patch('crypto/{id}', 'CryptoController@update');
Route::get('crypto/{id}/edit', 'CryptoController@edit');
Route::delete('crypto/{id}/destroy', 'CryptoController@destroy');

Route::get('port', 'PortController@index');
Route::post('port', 'PortController@store');
Route::get('port/json', 'PortController@json');
Route::get('port/create', 'PortController@create');
Route::get('port/{id}', 'PortController@show');
Route::patch('port/{id}', 'PortController@update');
Route::get('port/{id}/edit', 'PortController@edit');
Route::delete('port/{id}/destroy', 'PortController@destroy');

Route::get('address', 'AddressController@index');
Route::post('address', 'AddressController@store');
Route::get('address/json', 'AddressController@json');
Route::get('address/create', 'AddressController@create');
Route::get('address/{id}', 'AddressController@show');
Route::patch('address/{id}', 'AddressController@update');
Route::get('address/{id}/edit', 'AddressController@edit');
Route::delete('address/{id}/destroy', 'AddressController@destroy');

Route::get('miningProp', 'MiningPropController@index')->name('miningProp');
Route::get('miningProp/json', 'MiningPropController@json');
Route::get('miningProp/jsonHistoryAll', 'MiningPropController@jsonHistoryAll');
Route::get('miningProp/refresh', 'MiningPropController@refresh');
Route::get('miningProp/clear', 'MiningPropController@clear');
Route::get('miningProp/{id}', 'MiningPropController@show');
Route::get('miningProp/{id}/json', 'MiningPropController@jsonHistory');
Route::get('miningProp/{id}/clear', 'MiningPropController@historyClear');


Auth::routes();