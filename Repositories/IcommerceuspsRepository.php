<?php

namespace Modules\Icommerceusps\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface IcommerceuspsRepository extends BaseRepository
{

    public function calculate($parameters,$conf);

    public function getRates($postalCode, $weight, $items,$conf);

    public function addPackage($weight, $machinable, $shippingRates, $postalCode, $zipOrigin, $dimensions);

    public function getRatesInter($postalCode, $weight, $items, $country, $valueOfContents,$conf);

    public function addPackageInter($weight, $machinable, $valueOfContents, $country, $zipOrigin, $dimensions);


}
