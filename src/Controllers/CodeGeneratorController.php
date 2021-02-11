<?php

namespace ArsoftModules\CodeGenerator\Controllers;

use App\Http\Controllers\Controller;

class CodeGeneratorController extends Controller {

    /**
     * @return json
     */
    public function greeting()
    {
        $greeting = "Oit !";

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => $greeting
            ]
        ], 200);
    }
}