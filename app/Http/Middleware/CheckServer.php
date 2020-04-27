<?php

namespace App\Http\Middleware;
use App\Modules\Customers\Models\WebService;
use Closure;

class CheckServer
{
    public function handle($request, Closure $next)
    {
       $result =  WebService::where('name','srm')->first();
       //$result = \DB::table('web_services')->select('api_token')->where('name', 'srm')->first();
       //$ips = ['192.168.15.55', '192.168.15.95', '127.0.0.1'];
       //if (in_array( $request->ip(), $ips)){
           if($request->api_token === $result->api_token) {
                return $next($request);
            }else{
               dd('Wrong token!');
           }
       //}else{
         //  dd('your domain not allowed to fetch data from our api');
    //}
        //return $d='your domain not allowed to fetch data from our api';
    }
}
