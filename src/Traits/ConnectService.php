<?php

namespace Att\Responisme\Traits;

use Att\Responisme\Exceptions\StarterKitException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

use function PHPUnit\Framework\isNull;

/**
 * Configuration response for validate requests
 */
trait ConnectService
{
    protected $timeout = 3;
    protected $expireAt = 60; //in second

    public function callService($url, $method = null, $data = [])
    {
        if (empty($url)) {
            return response()->json([
                'success'   => false,
                'message'   => 'Define endpoint of service'
            ]);
        }

        $key = $url.($method ?? 'GET');
        $data = Redis::get($key);

        if (!empty($data) && !isNull($data)) {
            return json_decode($data);
        }

        $http = Http::acceptJson()
            ->retry(1,1)
            ->timeout($this->timeout)
            ->withHeaders(['Accept' => 'application/json', 'Authorization' => request()->header('authorization')]);

        $method = strtolower($method ?? 'get');
        $response = $http->$method($url, array_merge($data , ['auth_user_kong' => request('auth_user_kong')]))->json();                        
        $this->setRedis($key, $response);

        return $response;
        // if ($method === null || strtoupper($method) === 'GET') {
        //     return $http->get($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();                        
        // }elseif( strtoupper($method) === 'POST'){
        //     return $http->post($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        // }elseif( strtoupper($method) === 'PUT'){
        //     return $http->put($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        // }elseif( strtoupper($method) === 'PATCH'){
        //     return $http->patch($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        // }elseif( strtoupper($method) === "DELETE"){
        //     return $http->delete($url, array_merge($data ?? [], ['auth_user_kong' => request('auth_user_kong')]))->json();  
        // }
    }

    public function timeout($second = 3)
    {
        $this->timeout = $second;

        return $this;
    }

    public function setRedis($key, $value)
    {
        Redis::set($key, json_encode($value));
        Redis::expire($key, $this->expireAt);
    }

    public function expireAt($second = 60)
    {
        $this->expireAt = $second;

        return $this;
    }

}
