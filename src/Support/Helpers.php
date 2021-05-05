<?php

use Att\Responisme\Exceptions\StarterKitException;
use Att\Responisme\Support\Responisme;
use Illuminate\Support\Facades\Validator;

if (! function_exists('_validate')) {
    function _validate($request, array $rules = [], array $message = [])
    {
        if (empty($rules)) {
            throw (new StarterKitException('Validation rules must be define'))->withData($rules);
        }

        $validated = Validator::make($request, $rules, $message);

        if ($validated->fails()) {
            throw (new StarterKitException('The given data was invalid'))
                ->withCode('03')
                ->withData($validated->errors());
        }

        return $validated->validate();
    }
}

if (! function_exists('responisme')) {
    function responisme()
    {
        $factory = app(Responisme::class);

        return $factory;
    }
}

if (! function_exists('transMessageException')) {
    function transMessageException($exception, $default = 'Something when wrong. You may be able to try again.')
    {         
        if ($exception instanceof \Illuminate\Database\QueryException) {
            return $exception->getPrevious()->errorInfo[2];
        }else{
            return $default;
        }
    }
}

