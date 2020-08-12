<?php

namespace App\Http\Controllers\Api;

use App\Http\Models\Ciclo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CicloController extends Controller
{

    public function GetCiclos(){
        try
        {
            $ciclos = Ciclo::all();
            return response()->json(['status' => true, 
                'message'=> 'Ciclos encontrados',
                'body'=> $ciclos],
                200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function AddCiclo(Request $request){
        try{
        $repite = Ciclo::where('ciclo',$request->ciclo)->get();
        if (count($repite))
        {
            return response()->json(['status' => false,
                'message'=> 'Ya existe este ciclo',
                'body' => null],
                 400);
        }
        else
        {
            $ciclo = new Ciclo();
            $ciclo->ciclo = $request->ciclo;
            $ciclo->save();
            return response()->json(['status' => true,
                'message'=> 'Ciclo Agregado',
                'body'=> $ciclo],
                200);
        }
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function GetCiclosAño($ciclo){
        try
        {
            $ciclo0 = $ciclo . '-0';
            $ciclo1 = $ciclo . '-1';
            $ciclo2 = $ciclo . '-2';
            $ciclos = Ciclo::where('ciclo', $ciclo2)->orwhere('ciclo',$ciclo1)->orwhere('ciclo',$ciclo0)->get();
            return response()->json(['status' => true,
                'message'=> 'Ciclos encontrados',
                'body'=> $ciclos],
                200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function GetAñoCicloActual(){
        try {
            $mes = date("m");
            $year = date("Y");
            $response["year"] = $year;
            if ($mes <8)
            {
                $year = $year . '-1';
                $ciclo = Ciclo::where('ciclo',$year)->get();
                $response["ciclo"] = $ciclo;
            }
            else
            {
                $year = $year . '-2';
                $ciclo = Ciclo::where('ciclo',$year)->get();
                $response["ciclo"] = $ciclo;
            }
            return response()->json(['status' => true,
                'message'=> 'año y ciclo actual',
                'body'=> $response],
                200);
            
            
        } catch (\Exception $e) {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function getCurrentTerm(){
        $date = now();
        $term = $this->getTerm($date->month);
        $finalTerm = $date->year.$term;
        // dd($finalTerm);
        $response = Ciclo::where('ciclo', $finalTerm)->get();
        return response()->json(['status' => true,
                'message'=> 'año y ciclo actual',
                'body'=> $response],
                200);

    }
    public function getTerm($month) {
        if($month<8){
            return '-1';
        }
        return  '-2';
    }
}
