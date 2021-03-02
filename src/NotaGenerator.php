<?php

namespace ArsoftModules\NotaGenerator;

use App\Http\Controllers\Controller;

class NotaGenerator extends Controller {

    /**
     * @return json
     */
    public function generate($tableName)
    {
        $nota = "Oit : " . $tableName;
        return $nota;
    }
}