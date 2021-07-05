<?php

namespace Att\Responisme\Traits;

use Att\Responisme\Exceptions\StarterKitException;
use Illuminate\Http\Request;

/**
 * Configuration response for validate requests
 */
trait ValidateRequest
{
    public function makeValidating()
    {
        try {
            return response()->json(array_merge(request()->user()->toArray(), [
                'permissions' => request()->user()->getAllPermissions()->toArray(),
                'roles'       => request()->user()->getRoleNames()->toArray()
            ]));
        } catch (\Throwable $th) {
            throw (new StarterKitException(transMessageException($th)));
        }
    }

}
