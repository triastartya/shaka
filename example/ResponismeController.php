<?php

namespace App\Http\Controllers;

use Viershaka\Shaka\Exceptions\StarterKitException;

class ResponismeController extends Controller
{
    public function exception()
    {
        throw new StarterKitException('Error Exception');
    }

    public function exceptionWitData()
    {
        try {
            // str_replace();            
            \DB::table('random')->get();            
        } catch (\Throwable $th) {
            // throw (new StarterKitException('Error Exception'))->withData(['a' => 1, 'b' => 2]);
            // throw (new StarterKitException('Error Exception'))->withData($th)->withCode('02');            
            throw (new StarterKitException(transMessageException($th)))->withData($th)->withCode('02');
        }
    }

    public function responseSuccess()
    {
        return responisme()->makeSuccess('resp success', ['value' => true]);

        // return responisme()->withMessage('resp success')
        //     ->withData(['value' => true])
        //     ->withHttpCode(201)
        //     ->build();
    }

    public function responseError()
    {
        return responisme()->makeError('resp error', ['value' => false],'02');

        // return responisme()->withMessage('resp success')
        //     ->withData(['value' => true])
        //     ->withErrorCode('03')
        //     ->withHttpCode(401)
        //     ->build(false);
    }
}
