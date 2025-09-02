<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentacaoControlller extends Controller
{
    public function index($ver="v1")
    {
        if ($ver === "v2") {
            return view("dashboard-v2.documentacao");
        }
        return view("documentacao");
    }

}