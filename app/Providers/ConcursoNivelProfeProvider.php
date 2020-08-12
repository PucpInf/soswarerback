<?php
namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Http\Models\ConcursoNivelProfe;

class ConcursoNivelProfeProvider {
    public function getUpgrades () {
        $requests = ConcursoNivelProfe::select('id','idUsuario','estado','created_at','requestType')->where(request()->filter,request()->id)->get();
        return $requests;
    }
}
