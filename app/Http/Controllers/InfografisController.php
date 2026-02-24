<?php

namespace App\Http\Controllers;

use App\Models\Infografis;

class InfografisController extends Controller
{
    public function index()
    {
        $data = Infografis::orderBy('order')->get();

        return response()->json($data);
    }
}
