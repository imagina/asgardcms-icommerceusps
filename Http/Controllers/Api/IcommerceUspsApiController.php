<?php

namespace Modules\Icommerceusps\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

// Repositories
use Modules\Icommerceusps\Repositories\IcommerceuspsRepository;

use Modules\Icommerce\Repositories\ShippingMethodRepository;

class IcommerceUspsApiController extends BaseApiController
{

    private $icommerceusps;
    private $shippingMethod;
   
    public function __construct(
        IcommerceuspsRepository $icommerceusps,
        ShippingMethodRepository $shippingMethod
    ){
        $this->icommerceusps = $icommerceusps;
        $this->shippingMethod = $shippingMethod;
    }
    
    /**
     * Init data
     * @param Requests request
     * @param Requests array products - items (object)
     * @param Requests array products - total
     * @param Requests array options - countryCode
     * @param Requests array options - postCode
     * @param Requests array options - country
     * @return mixed
     */
    public function init(Request $request){
            
        try {

            // Configuration
            $shippingName = config('asgard.icommerceusps.config.shippingName');
            $attribute = array('name' => $shippingName);
            $shippingMethod = $this->shippingMethod->findByAttributes($attribute);

            $response = $this->icommerceusps->calculate($request->all(),$shippingMethod->options);

          } catch (\Exception $e) {
            //Message Error
            $status = 500;
            $response = [
              'errors' => $e->getMessage()
            ];
        }

        return response()->json($response, $status ?? 200);

    }
    
    

}