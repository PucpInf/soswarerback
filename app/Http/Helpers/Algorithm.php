<?php
use Illuminate\Support\Collection;

namespace App\Http\Helpers;

class Algorithm
{

 public function quitNullValues($array){
   $arrayFiltered = array_filter($array,function($var){
     return !is_null($var);
   });
   return $arrayFiltered;
 }

 public function quitNullValuesFromArray($array){
   $arrayFiltered = array_filter($array,function($var){
     return !is_null($var);
   });
   return $arrayFiltered;
 }

 public function quitNullValuesFromCollection($collection){
   // $array = $collection->all();
   // $arrayFiltered = array_filter($array,function($var){
   //   return !is_null($var);
   // });
   // $arrayFiltered;
   // $collectionFiltered = collect($arrayFiltered);
   // return $collectionFiltered;

   $collectionFiltered = $collection->filter(function($value, $key) {
      return  $value != null;
   });
   return $collectionFiltered->values();
 }



}
