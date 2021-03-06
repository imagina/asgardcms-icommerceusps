<?php

namespace Modules\Icommerceusps\Repositories\Eloquent;

use Modules\Icommerceusps\Repositories\IcommerceuspsRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

use Usps\Rate;
use Usps\RatePackage;

class EloquentIcommerceuspsRepository extends EloquentBaseRepository implements IcommerceuspsRepository
{

    function calculate($parameters,$conf){

        $valueTotal = $parameters["products"]["total"];
        $items = json_decode($parameters["products"]["items"]);
        //$items = $parameters["products"]["items"];

        $countryCode = isset($parameters["options"]["countryCode"]) ? $parameters["options"]["countryCode"] : null;
        $postalCode = isset($parameters["options"]["postalCode"]) ? $parameters["options"]["postalCode"] : null;
        $country = isset($parameters["options"]["country"]) ? $parameters["options"]["country"] : null;

        if ($postalCode != null && $countryCode != null){

            
            $weightTotal = icommerce_getTotalWeight($items, $countryCode); // Without Freeshiping
     
           if ($countryCode == "US")
                $response = $this->getRates($postalCode, $weightTotal, $items,$conf);
           else
                $response =$this->getRatesInter($postalCode, $weightTotal, $items, $country, $valueTotal,$conf);
            
         }else{
     
           return [
             'status' => 'error',
             'msj' => trans('icommerceusps::icommerceusps.messages.msjini')
           ];
     
         }

        return $response;

    }

    function getRates($postalCode, $weight, $items,$conf)
    {
    
        $actives = config('asgard.icommerceusps.config.actives.national');
        
        $resultMethods = [];
        $response["status"] = "error";
        
        $userID = $conf->userId;
        $shippingRates = $conf->shippingRates;
        $machinable = $conf->machinable;
        $zipOrigin = $conf->zipOrigin;
        
        try {
        
        $rate = new Rate($userID);
        
        $dimensions = icommerce_totalDimensions($items);
        
        $package = $this->addPackage($weight, $machinable, $shippingRates, $postalCode, $zipOrigin, $dimensions);
        
        $rate->addPackage($package);
        $rate->getRate();
        $result = $rate->getArrayResponse();
        
        if ($rate->isSuccess()) {
            
            $r = json_decode(json_encode($result));
            $resultEnd = $r->RateV4Response->Package->Postage;
            
            $caracterDel = array('&#8482;', '&#174;');
            
            //dd($resultEnd);
            
            if (count($resultEnd) > 1) {
                foreach ($resultEnd as $key => $rate) {
                    if ($rate->Rate > 0) {
                    
                    $name = strip_tags(htmlspecialchars_decode($rate->MailService));
                    $name = str_replace($caracterDel, "", $name);
                    
                    if(count($actives)>0){
                        if (icommerceusps_strposa($name, $actives)) {
                            $resultMethods[$key] = [
                            "configName" => $name,
                            "configTitle" => $name,
                            "price" => $rate->Rate
                            ];
                        }
                    }else{
                        $resultMethods[$key] = [
                            "configName" => $name,
                            "configTitle" => $name,
                            "price" => $rate->Rate
                        ];
                    }
                    
                    }
                }
            } else {
            
                //If it is 1 only result does not return an array
                $name = strip_tags(htmlspecialchars_decode($resultEnd->MailService));
                $name = str_replace($caracterDel, "", $name);
                if(count($actives)>0){
                    if (icommerceusps_strposa($name, $actives)) {
                        $resultMethods[0] = [
                        "configName" => $name,
                        "configTitle" => $name,
                        "price" => $resultEnd->Rate
                        ];
                    }else{
                        $response["msj"] = "Not Results";
                        return $response;
                    }
                } else {
                    $resultMethods[0] = [
                        "configName" => $name,
                        "configTitle" => $name,
                        "price" => $resultEnd->Rate
                    ];
                }
            }
            
            // If there were options but they were not added by name validation (config actives)
            if (count($resultMethods) == 0) {

                $response["msj"] = "Not Results";
                return $response;

            }
            
            $response["status"] = "success";
            $response["items"] = json_decode(json_encode($resultMethods));
            
            return $response;
            
        } else {
            
            $response["msj"] = $rate->getErrorMessage();
            return $response;
        }
        
        
        } catch (Exception $e) {

            $response["msj"] = $e->getMessage();
            return $response;

        }
    
    }

    function addPackage($weight, $machinable, $shippingRates, $postalCode, $zipOrigin, $dimensions)
    {
        
        $pounds = floor($weight); // Libras
        $ounces = number_format(($weight - floor($weight)) * 16, 2); // Onzas
        
        $package = new RatePackage();
        
        /***
         * required - Web Tool validates the entry to one of the service types.
         * Please see Appendix A for detailed business rules regarding combinations of Service, Container, dimensions and other request values.
         ***/
        if ($shippingRates == 0)
        $package->setService(RatePackage::SERVICE_ONLINE);
        else
        $package->setService(RatePackage::SERVICE_ALL);
        
        $package->setZipOrigination($zipOrigin);
        $package->setZipDestination($postalCode);
        $package->setPounds($pounds);
        $package->setOunces($ounces);
        
        /***
         * required - Use to specify special containers or container attributes that may affect postage; otherwise, leave blank.
         * default=VARIABLE whiteSpace=collapse
         * If one is indicated in specific, it may have individual requirements
         ***/
        $package->setContainer('');
        
        sort($dimensions);
        
        if (max($dimensions) > 12)
            $package->setSize(RatePackage::SIZE_LARGE);
        else
            $package->setSize(RatePackage::SIZE_REGULAR);
        
        /***
         * RateV4Request/Machinable is required when:
         * RateV4Request[Service='FIRST CLASS' and (FirstClassMailType='LETTER' or FirstClassMailType='FLAT')]
         * RateV4Request[Service='Retail Ground’] RateV4Request[Service='ALL'] RateV4Request[Service='ONLINE']
         * If false, First Class Mail Large Envelopes will not be returned.
         ***/
        if ($machinable == 0)
            $package->setField('Machinable', false);
        else
            $package->setField('Machinable', true);
        
        return $package;
    }

    // Rates Internationals

    function getRatesInter($postalCode, $weight, $items, $country, $valueOfContents,$conf)
    {
      
      $actives = config('asgard.icommerceusps.config.actives.international');
      
      $resultMethods = [];
      $response["status"] = "error";
      
      $userID = $conf->userId;
      $shippingRates = $conf->shippingRates;
      $machinable = $conf->machinable;
      $zipOrigin = $conf->zipOrigin;
     
      try {
        
        $rate = new Rate($userID);
        $rate->setInternationalCall(true);
        $rate->addExtraOption('Revision', 2);
        
        $dimensions = icommerce_totalDimensions($items);
        
        $package = $this->addPackageInter($weight, $machinable, $valueOfContents, $country, $zipOrigin, $dimensions);
        
        $rate->addPackage($package);
        
        $rate->getRate();
        $result = $rate->getArrayResponse();
        
        if ($rate->isSuccess()) {
          
          $r = json_decode(json_encode($result));
          
          if (isset($r->IntlRateV2Response->Package->Service)) {
            $resultEnd = $r->IntlRateV2Response->Package->Service;
          } else {
            
            $response["items"] = $r->IntlRateV2Response->Package->AreasServed;
            return $response;
          }
          
          $caracterDel = array('&#8482;', '&#174;');
          
          if (count($resultEnd) > 1) {
            foreach ($resultEnd as $key => $rate) {
              if ($rate->Postage > 0) {
                
                $name = strip_tags(htmlspecialchars_decode($rate->SvcDescription));
                $name = str_replace($caracterDel, "", $name);
                
                if(count($actives)>0){
                    if (icommerceusps_strposa($name, $actives)) {
                        $resultMethods[$key] = [
                            "configName" => $name,
                            "configTitle" => $name,
                            "price" => $rate->Postage
                        ];
                    }
                }else{
                    $resultMethods[$key] = [
                        "configName" => $name,
                        "configTitle" => $name,
                        "price" => $rate->Postage
                    ];
                }

              }
            }
          } else {
            
            //If it is 1 only result does not return an array
            $name = strip_tags(htmlspecialchars_decode($resultEnd->SvcDescription));
            $name = str_replace($caracterDel, "", $name);
            
            if(count($actives)>0){

                if (icommerceusps_strposa($name, $actives)) {
                    $resultMethods[0] = [
                        "configName" => $name,
                        "configTitle" => $name,
                        "price" => $resultEnd->Postage
                    ];
                }else{
                    $response["msj"] = "Not Results";
                    return $response;
                }

            } else {

                $resultMethods[0] = [
                    "configName" => $name,
                    "configTitle" => $name,
                    "price" => $resultEnd->Postage
                ];
              
            }

          }
          
          // If there were options but they were not added by name validation (config actives)
          if (count($resultMethods) == 0) {
            $response["msj"] = "Not Results";
            return $response;
          }
          
          $response["items"] = json_decode(json_encode($resultMethods));
          $response["status"] = "success";
          return $response;
          
        } else {
          $response["msj"] = $rate->getErrorMessage();
          return $response;
        }
        
      } catch (Exception $e) {
        $response["msj"] = $e->getMessage();
        return $response;
      }
      
    }

    function addPackageInter($weight, $machinable, $valueOfContents, $country, $zipOrigin, $dimensions)
    {
      
      $pounds = floor($weight); // Libras
      $ounces = number_format(($weight - floor($weight)) * 16, 2); // Onzas
      
      $package = new RatePackage;
      
      /***
       * Required - Value must be numeric. Package weight generally cannot exceed 70 pounds. Maximum decimal places are 8. Refer to the International Mail Manual (IMM) for weight requirements per country and mail service.
       ***/
      $package->setPounds($pounds);
      
      
      /***
       * Required - Value must be numeric. Package weight generally cannot exceed 70 pounds. Maximum decimal places are 8. Refer to the International Mail Manual (IMM) for weight requirements per country and mail service.
       ***/
      $package->setOunces($ounces);
      
      
      /***
       * Optional - Indicates whether or not the item is machinable. A surcharge is applied to a First-Class Mail International item if it has one or more non-machinable characteristics.
       ***/
      if ($machinable == 0)
        $package->setField('Machinable', false);
      else
        $package->setField('Machinable', true);
      
      
      /***
       * Required - Package type being shipped.
       * Examples: ALL, PACKAGE, POSTCARDS, ENVELOPE, LETTER, LARGEENVELOPE, FLATRATE
       ***/
      $package->setField('MailType', 'Package');
      
      
      /***
       * Required - If specified, used to compute Insurance fee (if insurance is available for service and destination).
       ***/
      $package->setField('ValueOfContents', $valueOfContents);
      
      
      /***
       * Required - Entries must be from the USPS list of valid countries from the International Country Listings.
       ***/
      $package->setField('Country', $country);
      
      
      /***
       * Required - Use to specify special containers or container attributes that may affect postage. Required when Size=”LARGE”.
       * Enumaration = RECTANGULAR , NONRECTANGULAR
       ***/
      $package->setField('Container', 'RECTANGULAR');
      
      /***
       * Required - REGULAR: dimensions are 12’’ or less; LARGE: dimensions are larger than 12’’.
       ***/
      
      $size = "REGULAR";
      
      sort($dimensions);
      
      if (max($dimensions) > 12)
        $size = 'LARGE';
      
      $package->setField('Size', $size);
      
      /***
       * 10-15-10 / 12-12-5 / 13-11-3
       * Required - Typically, the "thickness" of the package as measured in inches rounded to the nearest whole inch. Required to obtain GXG pricing and when Size=”LARGE”.
       ***/
      $package->setField('Width', round($dimensions[0]));
      
      /***
       * Required - The longest side of the package as measured in inches rounded to the nearest whole inch. Required to obtain GXG pricing and when Size=”LARGE”.
       ***/
      $package->setField('Length', round($dimensions[2]));
      
      
      /***
       * Required - The "height" of the package as measured in inches rounded to the nearest whole inch. Required to obtain GXG pricing and when Size=”LARGE”.
       ***/
      $package->setField('Height', round($dimensions[1]));
      
      /***
       * Required - The "girth" of the package as measured in inches rounded to the nearest whole inch. Required to obtain GXG pricing when pricing and when Size=”LARGE” and Container=”NONRECTANGULAR”.
       ***/
      $package->setField('Girth', 0);
      
      
      /***
       * Optional - Available when IntlRateV2Request [Revision='2'].
       *
       * Origin ZIP Code is required to determine Priority Mail International price to Canadian destinations and is used to determine mail-ability of Global Express Guaranteed. When provided, the response will return a list of Post Office locations where GXG is accepted. The Origin ZIP Code must be valid.
       ***/
      $package->setField('OriginZip', $zipOrigin);
      
      
      /***
       * Optional - Available when IntlRateV2Request[Revision='2'].
       *
       * Date and Time the package is accepted by USPS. The AcceptanceDateTime tag along with the DestinationPostalCode is used to calculate the GuaranteeAvailability response tag for PMEI services in Kahala countries.
       *
       * YYYY-MM-DDThh:mm:ss+/-hh:mm
       * For example, 2014-01-22T14:30:51-06:00
       ***/
      //$package->setField('AcceptanceDateTime', '2018-04-20T13:15:00-06:00');
      
      
      /***
       * Optional - Available when IntlRateV2Request[Revision='2'].
       * The AcceptanceDateTime tag along with the DestinationPostalCode is used to calculate the GuaranteeAvailability response tag for PMEI services in Kahala countries.
       ***/
      //$package->setField('DestinationPostalCode', $postalCode);
      
      return $package;
      
    }


}
