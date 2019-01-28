<?php

namespace Modules\IcommerceUsps\Http\Controllers;

use Modules\Core\Http\Controllers\BasePublicController;
use app\Http\Requests;
use Illuminate\Support\Facades\Request;

use Usps\Rate;
use Usps\RatePackage;
use Usps\FirstClassServiceStandards;
use Usps\ServiceDeliveryCalculator;

class PublicController extends BasePublicController
{
   
    public function __construct()
    {
        parent::__construct();

    }

    public function rateNational($zip){

        /*

            Postal Code Tests

            99205   =   Washington
            10001   =   Miami
            01030   =   Ciudad de Mexico
            110111  =   Bogota
        
        */
        
    
        $postalCode = $zip;
        $weight = 9;

        $results = icommerceusps_get_rates($postalCode,$weight);
        if($results["msj"]=="success"){
            foreach ($results["data"] as $key => $value) {
                echo "{$value->configName} - {$value->price} <br>";
                
            }
        }else{
            echo "{$results["msj"]} - {$results["data"]}";
        }

    }

    public function rateInternational($zip,$country,$value){

        $postalCode = $zip;
        $weight = 9;
        $valueOfContents = $value;

        $results = icommerceusps_get_ratesInter($postalCode,$weight,$country,$valueOfContents);

        if($results["msj"]=="success"){
            foreach ($results["data"] as $key => $value) {
                echo "{$value->configName} - {$value->price} <br>";
                
            }
        }else{
            echo "{$results["msj"]} - {$results["data"]}";
        }

    }


}