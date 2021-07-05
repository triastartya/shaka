<?php

namespace Att\Responisme\Traits;

use Att\Responisme\Exceptions\StarterKitException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Configuration response for validate requests
 */
trait ConnectService
{
    public function callService($url, $method = null, $data = null)
    {
        if (empty($url)) {
            return response()->json([
                'success'   => false,
                'message'   => 'Define endpoint of service'
            ]);
        }

        $http = Http::acceptJson()
            ->withHeaders(['Accept' => 'application/json', 'Authorization' => request()->header('authorization')]);

        if ($method === null || strtolower($method) === 'GET') {
            return $http->get($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();                        
        }elseif( strtolower($method) === 'POST'){
            return $http->post($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }elseif( strtolower($method) === 'PUT'){
            return $http->put($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }elseif( strtolower($method) === 'PATCH'){
            return $http->patch($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }elseif( strtolower($method) === "DELETE"){
            return $http->delete($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }
    }

}
