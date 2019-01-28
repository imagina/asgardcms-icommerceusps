<?php

namespace Modules\IcommerceUsps\Repositories\Cache;

use Modules\IcommerceUsps\Repositories\ConfiguspsRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheConfiguspsDecorator extends BaseCacheDecorator implements ConfiguspsRepository
{
    public function __construct(ConfiguspsRepository $configusps)
    {
        parent::__construct();
        $this->entityName = 'icommerceusps.configusps';
        $this->repository = $configusps;
    }
}
