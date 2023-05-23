<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class TextController extends Controller
{
    public function getText(Request $request)
    {

        $hosts = [
            'host' => '127.0.0.1',
            'port' => '9200',
            'scheme' => 'http',
            'user' => 'elastic',
            'pass' => 'CfPhE7CtnNCiSXDlSkJt'
        ];
//    $myCert = 'C:\elasticsearch\config\certs\http_ca.crt';
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])->setBasicAuthentication('elastic','CfPhE7CtnNCiSXDlSkJt')
            ->build();



        $index=\Illuminate\Support\Str::random();
        $paramss = [
            'index' => strval(strtolower($index)),
            'id' => strval(strtolower($index)),
            'body'  => ["text"=>$request['text']]
        ];
        $client->index($paramss);
        $response=$client->get([
            "index"=>strtolower($index),
            "id"=>strtolower($index)
        ]);

        $response = $client->indices()->analyze([
            'index' => strval(strtolower($index)),
            'body'  => [
                "analyzer"=>"stop",
                "text"=>$response['_source']['text'],
            ]
        ]);


        $finalarray=array();
        foreach ($response["tokens"] as $item){
            $finalarray[]=$item['token'];
        }

        $array_count_values = array_count_values($finalarray);
        arsort($array_count_values);
        return response()->json(['status'=>1,'result'=>$array_count_values]);





    }
    //
}
