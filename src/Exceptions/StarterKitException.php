<?php

namespace Viershaka\Shaka\Exceptions;

use Exception;

class StarterKitException extends Exception
{
    private $data = [];

    private $error_code = '01';

    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        return responisme()
            ->withData($this->data)
            ->withErrorCode($this->error_code)
            ->withMessage($this->getMessage())
            ->build(false);
    }

    public function withData($data)
    {
        if ($data instanceof \Throwable || $data instanceof \Exception) {
            $this->data = [
                'error' => $data->getMessage(),
            ];

            if (config('app.debug')) {
                $this->data = array_merge($this->data, [
                    'file'  => $data->getFile(),
                    'line'  => $data->getLine(),
                    'code'  => $data->getCode(),
                    'trace' => collect($data->getTrace())->map(function($value){
                        return collect($value)->except(['args']);
                    })
                ]);
            }
                        
            return $this;
        }

        $this->data = $data;

        return $this;
    }

    public function withCode($error_code)
    {
        $this->error_code = $error_code;

        return $this;
    }
}
