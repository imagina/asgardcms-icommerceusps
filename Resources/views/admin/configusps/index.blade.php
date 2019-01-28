@php
    
    $configuration = icommerceusps_get_configuration();
    $options = array('required' =>'required');

    if($configuration==NULL){
        $cStatus = 0;
    }else{
        $cStatus = $configuration->status;
    }
    $formID = uniqid("form_id");
   
@endphp

@if($configuration==NULL)
{!! Form::open(['route' => ["admin.icommerceusps.configusps.store"], 'method' => 'post','name' => $formID]) !!}
@else
{!! Form::open(['route' => ['admin.icommerceusps.configusps.update'], 'method' => 'put','name' => $formID]) !!}
@endif

<div class="col-xs-12">

    {!! Form::normalInput('user_id', '*'.trans('icommerceusps::configusps.table.user id'), $errors,$configuration,$options) !!}

    {!! Form::normalInput('zip_origin', '*'.trans('icommerceusps::configusps.table.zip origin'), $errors,$configuration,$options) !!}

    <div class="form-group">
        <label for="shipping_rates">*{{trans('icommerceusps::configusps.table.shipping rates')}}</label>
        <select class="form-control" id="shipping_rates" name="shipping_rates" required>
            <option value="0" @if(!empty($configuration) && $configuration->shipping_rates==0) selected @endif>ONLINE</option>
            <option value="1" @if(!empty($configuration) && $configuration->shipping_rates==1) selected @endif>ALL</option>
        </select>
        {{--
        <span class="label label-info">Choose which rates to show your customers, Click-N-Ship (ONLINE) rates are normally cheaper than OFFLINE</span>
        --}}
    </div>

    <div class="form-group">
        <label for="machinable">*{{trans('icommerceusps::configusps.table.machinable')}}</label>
        <select class="form-control" id="machinable" name="machinable" required>
            <option value="1" @if(!empty($configuration) && $configuration->machinable==1) selected @endif>YES</option>
            <option value="0" @if(!empty($configuration) && $configuration->machinable==0) selected @endif>NO</option>
        </select>
    </div>

    <div class="form-group">
        <div>
            <label class="checkbox-inline">
                <input name="status" type="checkbox" @if($cStatus==1) checked @endif>{{trans('icommerceusps::configusps.table.activate')}}
            </label>
        </div>   
    </div>

    <div class="infor" style="margin-top: 5px;margin-bottom: 15px;">
        <span class="label label-info">INFO</span>
        <ul>
            <li>Weight is in Pounds or Ounces (Not editable)</li>
        </ul>
    </div>
    
    <div class="box-footer">
    <button type="submit" class="btn btn-primary btn-flat">{{ trans('icommerceusps::configusps.button.save configuration') }}</button>
    </div>
   

</div>

{!! Form::close() !!}