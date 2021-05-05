<?php

namespace Att\StarterKit\Support;

class Responisme
{
    protected $data = [];

    protected $message = '';

    protected $http_code = '';

    protected $error_code = '01';

    public function withData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function withMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function withHttpCode($code)
    {
        $this->http_code = $code;

        return $this;
    }

    public function withErrorCode($error_code)
    {
        $this->error_code = $error_code;

        return $this;
    }

    public function makeSuccess($message = '', $data=[], $http_code = 200)
    {
        $this->withMessage($message);
        $this->withData($data);

        return response()->json(
            $this->makeResponse(['success'=> true]), $http_code);
    }

    public function makeError($message = '', $data=[], $error_code = '01', $http_code = 400)
    {
        $this->withMessage($message);
        $this->withData($data);
        $this->withErrorCode($error_code);

        return response()->json(
            $this->makeResponse(['success'=> false]), $http_code);
    }

    public function build(bool $status = true)
    {
        if ($status) {        
            return response()->json(
                $this->makeResponse(['success'=> true]), 
                $this->http_code !== '' ? $this->http_code : 200);
        }

        return response()->json(
            $this->makeResponse(['success'=> false]), 
            $this->http_code !== '' ? $this->http_code : 400);
    }

    public function makeResponse(array $status)
    {
        if(!empty($this->message) && !is_null($this->message)){
            $status = array_merge($status, ['message' => $this->message]);
        }

        if (!$status['success']) {
            $status = array_merge($status, ['code' => $this->error_code]);
        }
        
        if(!empty($this->data) && !is_null($this->data)){
            $status = array_merge($status, ['data' => $this->data]);
        };

        return $status;
    }

}
