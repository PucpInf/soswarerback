<?php

namespace App\Http\Controllers\Api;
use App\Http\Models\Facultad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultadController extends Controller
{
    public function getFacultades(){
        $facus = Facultad::all();
        $response = array(
            'status' => true,
            'message' => 'Facus encontradas',
            'body' => $facus
        );
        return response()->json($response);
    }
}
