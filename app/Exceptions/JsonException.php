<?php

namespace App\Exceptions;

use Exception;

class JsonException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage()
        ], !empty($this->getCode()) ? $this->getCode() : 200);
    }
}
