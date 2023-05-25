<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebController extends Controller
{
    public function index()
    {
        return view('welcome');

    }

    public function submitText(Request $request)
    {



        $deleteresponse = Http::withBasicAuth('elastic', 'rJuvX56xBE4_erA+VOL5')->delete('http://localhost:9200/my-index-000001');


        $responseee = Http::withBasicAuth('elastic', 'rJuvX56xBE4_erA+VOL5')->withBody(
            '
            {
  "settings": {
    "analysis": {
      "analyzer": {
        "english_stop": {
          "type":       "stop",
          "stopwords":  "_persian_"
        }
      }
    }
  }
}
              ','application/json'
        )->put('http://localhost:9200/my-index-000001');


        $response = Http::withBasicAuth('elastic', 'rJuvX56xBE4_erA+VOL5')->withBody(
            json_encode([
                'analyzer'=>"english_stop",
                'text'=>$request['text'],
            ]),'application/json'
        )->post('http://localhost:9200/my-index-000001/_analyze');
        $finalarray = array();
        foreach (json_decode($response->body(),true)['tokens'] as $item) {
            $finalarray[] = $item['token'];
        }


        $array_count_values = array_count_values($finalarray);
        arsort($array_count_values);
        dd($array_count_values);

    }
    //
}
