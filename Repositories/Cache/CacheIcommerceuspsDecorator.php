<?php

namespace Modules\Icommerceusps\Repositories\Cache;

use Modules\Icommerceusps\Repositories\IcommerceuspsRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheIcommerceuspsDecorator extends BaseCacheDecorator implements IcommerceuspsRepository
{
    public function __construct(IcommerceuspsRepository $icommerceusps)
    {
        parent::__construct();
        $this->entityName = 'icommerceusps.icommerceusps';
        $this->repository = $icommerceusps;
    }

     /**
   * List or resources
   *
   * @return mixed
   */
    public function calculate($parameters,$conf)
    {
        return $this->remember(function () use ($parameters,$conf) {
            return $this->repository->calculate($parameters,$conf);
        });
    }

    /**
     * List or resources
     *
     * @return mixed
     */
    public function getRates($conf,$postalCode,$weight,$items)
    {
        return $this->remember(function () use ($conf,$postalCode,$weight,$items) {
            return $this->repository->getRates($conf,$postalCode,$weight,$items);
        });
    }

    /**
     * add package
     *
     * @return package
     */
    public function addPackage($weight, $machinable, $shippingRates, $postalCode, $zipOrigin, $dimensions){
        return $this->remember(function () use ($weight, $machinable, $shippingRates, $postalCode, $zipOrigin, $dimensions) {
            return $this->repository->addPackage($weight, $machinable, $shippingRates, $postalCode, $zipOrigin, $dimensions);
        });
    }

    /**
     * List or resources
     *
     * @return mixed
     */
    public function getRatesInter($postalCode, $weight, $items, $country, $valueOfContents,$conf)
    {
        return $this->remember(function () use ($postalCode, $weight, $items, $country, $valueOfContents,$conf) {
            return $this->repository->getRates($postalCode, $weight, $items, $country, $valueOfContents,$conf);
        });
    }

    /**
     * add package
     *
     * @return package
     */
    public function addPackageInter($weight, $machinable, $valueOfContents, $country, $zipOrigin, $dimensions){
        return $this->remember(function () use ($weight, $machinable, $valueOfContents, $country, $zipOrigin, $dimensions) {
            return $this->repository->addPackage($weight, $machinable, $valueOfContents, $country, $zipOrigin, $dimensions);
        });
    }



}
