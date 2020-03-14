<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// listar cidadãos
Route::get('cidadaos', 'CidadaoController@index');

// lidando com tentativas de acessar o endpoint através de outros métodos
Route::post('cidadaos', 'CidadaoController@indexErro');
Route::patch('cidadaos', 'CidadaoController@indexErro');
Route::put('cidadaos', 'CidadaoController@indexErro');
Route::delete('cidadaos', 'CidadaoController@indexErro');

// consultar um cidadão através de seu cpf
Route::get('cidadao/{cpf}', 'CidadaoController@show');

// mostrar erro quando acessa o endpoint e não informa o cpf
Route::get('cidadao', 'CidadaoController@cpfErro');

// inserir um cidadão
Route::post('cidadao', 'CidadaoController@store');

// altera dados de um cidadão
Route::put('cidadao/{cpf}', 'CidadaoController@update');
Route::patch('cidadao/{cpf}', 'CidadaoController@update');

// mostrar erro quando acessa o endpoint e não informa o cpf
Route::put('cidadao', 'CidadaoController@cpfErro');
Route::patch('cidadao', 'CidadaoController@cpfErro');

// remove um registro de cidadão
Route::delete('cidadao/{cpf}', 'CidadaoController@destroy');

// mostrar erro quando acessa o endpoint e não informa o cpf
Route::delete('cidadao', 'CidadaoController@cpfErro');
