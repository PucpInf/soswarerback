<?php
namespace App\Http\Services;

use App\Http\Models\License;
use Illuminate\Support\Facades\DB;


class LicenseService {
  public function register ($licenseRequest) {
    $license = array(
      "userId" => $licenseRequest['userId'],
      "dedication" => $licenseRequest['dedication'],
      "licenseFirstDate" => $licenseRequest['licenseFirstDate'],
      "licenseLastDate" => $licenseRequest['licenseLastDate'],
      "state" => 'Pendiente',
      "licensePlace" => $licenseRequest['licensePlace'],
      "reason" => $licenseRequest['reason']
    );
    $licenseResponse = License::create($license);

    return $licenseResponse;
  }
}




 ?>
