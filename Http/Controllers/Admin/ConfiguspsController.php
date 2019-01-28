<?php

namespace Modules\IcommerceUsps\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\IcommerceUsps\Entities\Configusps;
use Modules\IcommerceUsps\Http\Requests\CreateConfiguspsRequest;
use Modules\IcommerceUsps\Http\Requests\UpdateConfiguspsRequest;
use Modules\IcommerceUsps\Repositories\ConfiguspsRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Setting\Repositories\SettingRepository;

class ConfiguspsController extends AdminBaseController
{
  /**
   * @var ConfiguspsRepository
   */
  private $configusps;
  
  public function __construct(ConfiguspsRepository $configusps, SettingRepository $setting)
  {
    parent::__construct();
    
    $this->configusps = $configusps;
    $this->setting = $setting;
  }
  
  /**
   * Store a newly created resource in storage.
   *
   * @param  CreateConfiguspsRequest $request
   * @return Response
   */
  public function store(CreateConfiguspsRequest $request)
  {
    
    $this->configusps->create($request->all());
    
    return redirect()->route('admin.icommerce.shipping.index')
      ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('icommerceusps::configusps.title.configusps')]));
  }
  
  /**
   * Update the specified resource in storage.
   *
   * @param  Configusps $configusps
   * @param  UpdateConfiguspsRequest $request
   * @return Response
   */
  public function update(Configusps $configusps, UpdateConfiguspsRequest $request)
  {
    
    if ($request->status == 'on')
      $request['status'] = "1";
    else
      $request['status'] = "0";
    
    $data = $request->all();
    $token = $data['_token'];
    unset($data['_token']);
    unset($data['_method']);
    
    $newData['_token'] = $token;
    foreach ($data as $key => $val)
      $newData['icommerceusps::' . $key] = $val;
    
    $this->setting->createOrUpdate($newData);
    
    return redirect()->route('admin.icommerce.shipping.index')
      ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('icommerceusps::configusps.title.configusps')]));
  }
  
  
}
