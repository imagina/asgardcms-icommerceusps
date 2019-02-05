<?php

namespace Modules\Icommerceusps\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Icommerce\Entities\ShippingMethod;

class IcommerceuspsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $options['init'] = "Modules\Icommerceusps\Http\Controllers\Api\IcommerceUspsApiController";
        $options['userId'] = "";
        $options['zipOrigin'] = "";
        $options['shippingRates'] = 1;
        $options['machinable'] = 0;

        $params = array(
            'title' => trans('icommerceusps::icommerceusps.single'),
            'description' => trans('icommerceusps::icommerceusps.description'),
            'name' => config('asgard.icommerceusps.config.shippingName'),
            'status' => 0,
            'options' => $options
        );

        ShippingMethod::create($params);

    }
}
