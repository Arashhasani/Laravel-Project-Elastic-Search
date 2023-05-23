<?php

use Elasticsearch\ClientBuilder;

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

Route::prefix('/')->namespace('App\Http\Controllers\Web')->group(function (){
    Route::get('/','WebController@index')->name('first.page');
    Route::post('/text','WebController@submitText')->name('submit.text');
});


Route::get('/', function () {
    return view('welcome');


//    $hosts = ['https://localhost:9200'];


    $hosts = [
        'host' => '127.0.0.1',
        'port' => '9200',
        'scheme' => 'http',
        'user' => 'elastic',
        'pass' => 'CfPhE7CtnNCiSXDlSkJt'
    ];
//    $myCert = 'C:\elasticsearch\config\certs\http_ca.crt';
    $client = ClientBuilder::create()
        ->setHosts(['http://localhost:9200'])->setBasicAuthentication('elastic', 'CfPhE7CtnNCiSXDlSkJt')
        ->build();


    $index = \Illuminate\Support\Str::random();
    $paramss = [
        'index' => strval(strtolower($index)),
        'id' => strval(strtolower($index)),
        'body' => ["text" => "The 2 QUICK Brown-Foxes jumped jumped over the lazy dog's bone."]
    ];
    $client->index($paramss);
    $response = $client->get([
        "index" => strtolower($index),
        "id" => strtolower($index)
    ]);

    // Set the index and type
    $params = [
        'index' => 'my_index',
        'body' => [
            'query' => [
                'match' => [
                    'testField' => 'a'
                ]
            ]
        ]
    ];

    $response = $client->indices()->analyze([
        'index' => strval(strtolower($index)),
        'body' => [
            "analyzer" => "stop",
            "text" => $response['_source']['text'],
        ]
    ]);


//    $response = $client->indices()->create($params);
//    $response = $client->index($params);
//    $response = $client->search(['index'=>'reuters']);
    $finalarray = array();
    foreach ($response["tokens"] as $item) {
        $finalarray[] = $item['token'];
    }

//    $responsee = $client->indices()->analyze([
//        'index' => strval(strtolower($index)),
//        'body' => [
//            "tokenizer" => "standard",
//            "filter" => ["stemmer"],
//            "text" => "The 2 QUICKing Brown-Foxes jumped jumped over the lazy dog's bone.",
//        ]
//    ]);
//    dd($responsee);
//    dd(implode(' ', $finalarray));

    $array_count_values = array_count_values($finalarray);
    arsort($array_count_values);
    dd($array_count_values);
});
