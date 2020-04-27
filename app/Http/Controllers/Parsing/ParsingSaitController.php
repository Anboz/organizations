<?php

namespace App\Http\Controllers\Parsing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParsingSaitController extends Controller
{
    function pars(Request $request){

        $params = array();
        $params["__EVENTTARGET"] = "";
        $params["__EVENTARGUMENT"] = "";
        $params["__VIEWSTATE"] = "/wEPDwULLTEwMTE4MTU1NzIPZBYCAgMPZBYIAgEPDxYCHgRUZXh0BSrQoNC10LXRgdGC0YAg0Y7RgNC40LTQuNGH0LXRgdC60LjRhSDQu9C40YZkZAIFDzwrAAUBAzwrAAkBCDwrAAQBAg9kEBYDZgIBAgIWAxQrAAEWBh8ABQNFSU4eBVZhbHVlBQExHg5SdW50aW1lQ3JlYXRlZGcUKwABFgYfAAUDSU5OHwEFATIfAmcUKwABFgYfAAUY0J3QsNC40LzQtdC90L7QstCw0L3QuNC1HwEFATMfAmdkZAIHDzwrAAYBAA8WAh8ABQrQn9C+0LjRgdC6ZGQCCQ88KwAYAgYPZBAWBWYCAQICAgMCBBYFPCsACwEAFgIeB0NhcHRpb24FA0VJTjwrAAsBABYCHwMFA0lOTjwrAAsBABYCHwMFHdCf0L7Qu9C90L7QtSDQvdCw0LfQstCw0L3QuNC1PCsACwEAFgIfAwUl0JTQsNGC0LAg0YDQtS08YnIvPtCz0LjRgdGC0YDQsNGG0LjQuDwrAAsBABYCHwMFDNCh0YLQsNGC0YPRgQ8WBQIBAgECAQIBAgEWAQWZAURldkV4cHJlc3MuV2ViLkFTUHhHcmlkVmlldy5HcmlkVmlld0RhdGFUZXh0Q29sdW1uLCBEZXZFeHByZXNzLldlYi5BU1B4R3JpZFZpZXcudjEyLjEsIFZlcnNpb249MTIuMS41LjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49Yjg4ZDE3NTRkNzAwZTQ5YRU8KwAGAQUUKwACZGRkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYDBRFBU1B4Q29tYm9Cb3gxJERERAULQVNQeEJ1dHRvbjEFDUFTUHhHcmlkVmlldzGwYK+vauOn1gRprY9MCMjs3ltgoVRgbM/CEVWGmuHKRA==";
        $params["__VIEWSTATEGENERATOR"] = "C3C4B49A";
        $params["ASPxTextBox1_Raw"] = "";
        $params["ASPxTextBox1"] = "Все";
        $params["ASPxComboBox1_VI"] = "";
        $params["ASPxComboBox1"] = "";
        $params["ASPxComboBox1_DDDWS"] = "0:0:-1:-10000:-10000:0:-10000:-10000:1";
        $params['ASPxComboBox1$DDD$L'] = "";
        $params['ASPxGridView1$DXSelInput'] = "";
        $params['ASPxGridView1$DXKVInput'] = "[]";
        $params["DXScript"] = "1_44,1_76,2_34,2_41,2_33,1_69,1_67,2_36,2_27,1_54,3_7";
        $params["__CALLBACKID"] = "ASPxGridView1";
        $params["__EVENTVALIDATION"] = "/wEdAALEeCLODBFfoIfenYccjkNcWejaXvDbZ/lQKg92TF5+5alrFF2bo3HWMZymWVE2buJLpy9v4FlnC6FzZXOJiJes";
        $url = "https://registry.andoz.tj/legal.aspx?lang=ru";
        $ch = curl_init($url);
//curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_SSl_VERIFYHOST, false);



// Указываем, что у нас POST запрос
        curl_setopt($ch, CURLOPT_POST, true);
// Добавляем переменные
        $i = $_POST['i'];
        $m = $i;

        $k = $i - 1;
        $params['ASPxGridView1$CallbackState'] = file_get_contents("pages/$i.txt");
        if ($i < 10)
            $params["__CALLBACKPARAM"] = "c0:KV|2;[];GB|20;12|PAGERONCLICK3|PN$k;";
        else
            $params["__CALLBACKPARAM"] = "c0:KV|2;[];GB|20;12|PAGERONCLICK4|PN$k;";

        $data = http_build_query($params);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);
//echo($output);
        curl_close($ch);
          $this->convertToArray($output, $i.".json");
    }

      private function convertToArray($content, $fname)
      {
          $array = explode('<td class="dxgv"', $content);
          $n = count($array);
          $data = array();
          for ($i = 0; $i < $n; $i++) {
              if ($i % 51 == 0)
                  continue;
              $array[$i] = explode('>', explode("<", $array[$i])[0])[1];
              $data[] = $array[$i];

          }

          $array = array_chunk($data, 5);
          $n = count($array);
          $organisations = array();
          for ($i = 0; $i < $n; $i++) {
              $organisations[$i] = [  "ein"=>$array[$i][0],
                  "inn"=>$array[$i][1],
                  "name"=>$array[$i][2],
                  "date"=>$array[$i][3],
                  "status"=>$array[$i][4]
              ];


          }

        /*  //Encode the array into a JSON string.
          $encodedString = json_encode($organisations);

          //Save the JSON string to a text file.
          file_put_contents("data/$fname.json", $encodedString);
     /*
                //Retrieve the data from our text file.
                $fileContents = file_get_contents("data/$fname.json");

                //Convert the JSON string back into an array.
                $decoded = json_decod$fileContents, true);

                      //The end result.
                      var_dump($decoded);
                    //
     */
                         echo("<pre>");
                       print_r($organisations);
                       echo("<pre>");


      }
    
}
