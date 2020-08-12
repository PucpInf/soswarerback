<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class RequestProvider {

  public function getAllRequests(){
    
    $economicSupports = app('App\Http\Controllers\Api\ApoyoEconomicoController')->get();
    $licenses = app('App\Http\Controllers\Api\LicenseController')->get();
    $downloads = app('App\Http\Controllers\Api\DownloadController')->get();
    if(request()->filter == 'idUsuario'){
      $upgrades = app('App\Http\Controllers\Api\ConcursoNivelProfeController')->get();
      $upgradesRequests = new Collection();
    }
    $states = request()->states;
    $requests = new Collection();
    $economicRequests = new Collection();
    $licenseRequests = new Collection();
    $downloadRequests = new Collection();
    foreach ($states as $state) {
      $economicRequests = $economicRequests->merge($economicSupports->where('estado', $state));
      $licenseRequests = $licenseRequests->merge($licenses->where('estado', $state));
      $downloadRequests = $downloadRequests->merge($downloads->where('estado', $state));
      if(request()->filter == 'idUsuario'){
        $upgradesRequests = $upgradesRequests->merge($upgrades->where('estado', $state));
      }
    }
    $requests = $requests->concat($economicRequests);
    $requests = $requests->concat($licenseRequests);
    $requests = $requests->concat($downloadRequests);
    if(request()->filter == 'idUsuario'){
      $requests = $requests->concat($upgradesRequests);
    }
    
    return $requests;
  }

  public function getRequestsByType () {
    $controller = 'App\Http\Controllers\Api'.'\\'.request()->requestType.'Controller';
    $requests = app($controller)->get();
    $collection = new Collection();
    foreach (request()->states as $state) {
      $collection = $collection->merge($requests->where('estado', $state));
    }
    return $collection;
  }

  public function postRequest() {
    switch (request()->requestType) {
      case '1':
        return app('App\Http\Controllers\Api\ApoyoEconomicoController')->store();
        break;
      case '2':
        return app('App\Http\Controllers\Api\LicenseController')->store();
        break;
      case '3':
        return app('App\Http\Controllers\Api\DownloadController')->store();
        break;
      default:
        return 0;
        break;
    }
  }

  public function getRequest(){
    // switch (request()->requestType) {
      // case '1':
        // return 'keekeke';
        // break;
      if(request()->requestType == 'Apoyo Economico'){
        $request =  app('App\Http\Controllers\Api\ApoyoEconomicoController')->getRequest();

      }
      if(request()->requestType == 'Licencia'){
        $request =  app('App\Http\Controllers\Api\LicenseController')->getRequest();

      }
      if(request()->requestType == 'Descarga'){
        $request =  app('App\Http\Controllers\Api\DownloadController')->getRequest();
      }
      // $applicant = app('App\Http\Controllers\PersonController')->get($request->userId);

      // $request->person = $applicant;
      return $request;
  }

  public function responseRequest(){
    $controller = 'App\Http\Controllers\Api'.'\\'.request()->requestType.'Controller';
    $response = app($controller)->responseRequest();
    return $response;
  }
}

 ?>
