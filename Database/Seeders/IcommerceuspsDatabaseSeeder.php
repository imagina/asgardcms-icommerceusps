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

        $titleTrans = 'icommerceusps::icommerceusps.single';
        $descriptionTrans = 'icommerceusps::icommerceusps.description';

        foreach (['en', 'es'] as $locale) {

            if($locale=='en'){
                $params = array(
                    'title' => trans($titleTrans),
                    'description' => trans($descriptionTrans),
                    'name' => config('asgard.icommerceusps.config.shippingName'),
                    'status' => 0,
                    'options' => $options
                );

                $shippingMethod = ShippingMethod::create($params);
                
            }else{

                $title = trans($titleTrans,[],$locale);
                $description = trans($descriptionTrans,[],$locale);

                $shippingMethod->translateOrNew($locale)->title = $title;
                $shippingMethod->translateOrNew($locale)->description = $description;

                $shippingMethod->save();
            }

        }// Foreach

    }
}
