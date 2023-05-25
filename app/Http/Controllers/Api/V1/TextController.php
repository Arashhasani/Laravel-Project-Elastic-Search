<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TextController extends Controller
{
    public function getText(Request $request)
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
        $corectcentexe=implode(' ',$finalarray);

        $array_count_values = array_count_values($finalarray);
        arsort($array_count_values);
        return response()->json(['status'=>1,'result'=>$array_count_values,'min'=>min(array_values($array_count_values)),'max'=>max(array_values($array_count_values)),'values'=>array_values($array_count_values),'keys'=>array_keys($array_count_values),'correct'=>$corectcentexe]);





    }
    //
}
