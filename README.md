# asgardcms-icommerceusps

## Seeder

    run php artisan module:seed Icommerceusps

## Vendors

    - add composer.json 
        "johnpaulmedina/laravel-usps":"dev-master"

    - add the service provider to config/app.php
        Usps\UspsServiceProvider::class,

    - Then add an alias under aliases array.
        'Usps' => Usps\Facades\Usps::class,
    

## Configurations

    - User ID
    - Zip Origin
    - Shipping Rates
    - Machinable

## API
    
    ### Parameters
        * @param Request (products,options)
        * @param Request array "products" - items (object) 
        * @param Request array "products" - total (float)
        * @param Request array "options" - countryCode (string)
        * @param Request array "options" - postCode (varchar)
        * @param Request array "options" - country (string)

    ### Example
        https://mydomain/api/icommerceusps
