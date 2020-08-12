<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipoCategoria;

class TipoCategoriaController extends Controller
{

	public function GetCategorias()
	{
		try
		{
			$categorias = TipoCategoria::all();
	    	return response()->json(['status' => true, 
                'message'=> 'TipoCategoria encontrados', 
                'body'=> $categorias], 
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
    
}
