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
    protected $timeout = 3;

    public function callService($url, $method = null, $data = null)
    {
        if (empty($url)) {
            return response()->json([
                'success'   => false,
                'message'   => 'Define endpoint of service'
            ]);
        }

        $http = Http::acceptJson()
            ->timeout($this->timeout)
            ->withHeaders(['Accept' => 'application/json', 'Authorization' => request()->header('authorization')]);

        if ($method === null || strtoupper($method) === 'GET') {
            return $http->get($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();                        
        }elseif( strtoupper($method) === 'POST'){
            return $http->post($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }elseif( strtoupper($method) === 'PUT'){
            return $http->put($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }elseif( strtoupper($method) === 'PATCH'){
            return $http->patch($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }elseif( strtoupper($method) === "DELETE"){
            return $http->delete($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        }
    }

    public function timeout($second = 3)
    {
        $this->timeout = $second;

        return $this;
    }

}
