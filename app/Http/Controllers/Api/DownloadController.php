<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Providers\DownloadProvider;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    //
    protected $downloadProvider;

    public function __construct(){

      $this->downloadProvider = new DownloadProvider();
    }
    public function store(){
      $this->validate(request(), [
        'horasDescarga' => 'required',
        'razonDescarga' => 'required'
      ]);
      $download = $this->downloadProvider->submitForm();

      return $download;
    }

    public function get(){
      $downloads = $this->downloadProvider->getUserDownloads();
      return $downloads;

    }

    // public function getSectionRequests(){
    //   $downloads = $this->downloadProvider->getSectionRequests();
    //   return $downloads;
    // }

    public function getRequest(){
      $request = $this->downloadProvider->getRequest();
      return $request;
    }
    public function responseRequest(){
      $response = $this->downloadProvider->response();
      return $response;
    }
}
