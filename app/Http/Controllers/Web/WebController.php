<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        return view('welcome');

    }

    public function submitText(Request $request)
    {


        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])->setBasicAuthentication('elastic', 'rJuvX56xBE4_erA+VOL5')
            ->build();


        $index = \Illuminate\Support\Str::random();
        $paramss = [
            'index' => strval(strtolower($index)),
            'id' => strval(strtolower($index)),
            'body' => ["text" => $request['text']]
        ];
        $client->index($paramss);
        $response = $client->get([
            "index" => strtolower($index),
            "id" => strtolower($index)
        ]);



        $response = $client->indices()->analyze([
            'index' => strval(strtolower($index)),
            'body' => [
                "analyzer" => "stop",
                "text" => $response['_source']['text'],
            ]
        ]);


        $finalarray = array();
        foreach ($response["tokens"] as $item) {
            $finalarray[] = $item['token'];
        }


        $array_count_values = array_count_values($finalarray);
        arsort($array_count_values);
        dd($array_count_values);

    }
    //
}
