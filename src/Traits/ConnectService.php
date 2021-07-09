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
    protected $timeout = 1; //in seconds
    protected $expireAt = 60; //in seconds
    protected $redis = true;

    public function callService($url = '', $method = null, $data = [])
    {
        if (empty($url)) {
            return ['success' => false, 'message' => 'Endpoint of service must be define'];
        }

        $key = $url.($method ?? 'GET').json_encode($data);

        if ($this->redis) {                
            $cache = Redis::get($key);

            if (!empty($cache)) {
                return json_decode($cache);
            }
        }

        return $this->sendRequest($url, $method, $data, $key);                                
    }

    private function sendRequest($url, $method, $data, $key)
    {
        try {
            $sessionKey = $this->sessionKey($url);

            // check service is up base on history request
            if (session()->has($sessionKey)){                
                return ['success' => false, 'message' => session($sessionKey)];
            } 

            $http = Http::acceptJson()
                ->retry(1,1)
                ->timeout($this->timeout)
                ->withHeaders(['Accept' => 'application/json', 'Authorization' => request()->header('authorization')]);

            $method = strtolower($method ?? 'get');

            $response = $http->$method($url, array_merge($data , ['auth_user_kong' => request('auth_user_kong')]))->json();                        
            
            if ($this->redis) {
                $this->setRedis($key, $response);
            }

            return $response;                       
        } catch (\Throwable $th) {
            session()->flash($sessionKey, $th->getMessage());

           return ['success' => false, 'message' => $th->getMessage()];
        }
        
    }

    private function sessionKey($url)
    {
        $parse = parse_url($url);

        return ($parse['host'] ?? $url).($parse['port'] ?? '');
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

    public function enableRedis()
    {
        $this->redis = true;

        return $this;
    }

    public function disableRedis()
    {
        $this->redis = false;

        return $this;
    }

}
