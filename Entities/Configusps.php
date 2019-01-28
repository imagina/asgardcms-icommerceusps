<?php

namespace Modules\IcommerceUsps\Entities;



class Configusps
{
    

    private $user_id;
    private $zip_origin;
    private $shipping_rates;
    private $size;
    private $machinable;
    private $status;
  
  public function __construct()
  {
    $this->user_id = setting('icommerceusps::user_id');
    $this->zip_origin = setting('icommerceusps::zip_origin');
    $this->shipping_rates = setting('icommerceusps::shipping_rates');
    $this->size = setting('icommerceusps::size');
    $this->machinable = setting('icommerceusps::machinable');
    $this->status = setting('icommerceusps::status');
    
  }
  
  public function getData()
  {
    return (object) [
      'user_id' => $this->user_id,
      'zip_origin' => $this->zip_origin,
      'shipping_rates' => $this->shipping_rates,
      'size' => $this->size,
      'machinable' => $this->machinable,
      'status' => $this->status,
    ];
  }
  
  public function calculate($cart){
  
  }

}
