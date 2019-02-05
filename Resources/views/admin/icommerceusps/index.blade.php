@php
    $options = array('required' =>'required');
    $formID = uniqid("form_id");
@endphp

{!! Form::open(['route' => ['admin.icommerce.shippingmethod.update',$method->id], 'method' => 'put','name' => $formID]) !!}

<div class="col-xs-12 col-sm-9">

    <div class="row">

        <div class="nav-tabs-custom">
            @include('partials.form-tab-headers')
            <div class="tab-content">
                <?php $i = 0; ?>
                @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                    <?php $i++; ?>
                    <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="{{$method->name}}_tab_{{ $i }}">
                        
                        {!! Form::i18nInput('title', '* '.trans('icommerce::paymentmethods.table.title'), $errors, $locale, $method) !!}
                        {!! Form::i18nInput('description', '* '.trans('icommerce::paymentmethods.table.description'), $errors, $locale, $method) !!}
                    
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>

    <div class="row">
    <div class="col-xs-12">

        <div class="form-group ">
            <label for="userId">* {{trans('icommerceusps::icommerceusps.table.userId')}}</label>
            <input placeholder="{{trans('icommerceusps::icommerceusps.table.userId')}}" required="required" name="userId" type="text" id="userId" class="form-control" value="{{$method->options->userId}}">
        </div>

        <div class="form-group ">
            <label for="zipOrigin">* {{trans('icommerceusps::icommerceusps.table.zipOrigin')}}</label>
            <input placeholder="{{trans('icommerceusps::icommerceusps.table.zipOrigin')}}" required="required" name="zipOrigin" type="text" id="zipOrigin" class="form-control" value="{{$method->options->zipOrigin}}">
        </div>

        <div class="form-group">
            <label for="shippingRates">* {{trans('icommerceusps::icommerceusps.table.shippingRates')}}</label>
            <select class="form-control" id="shippingRates" name="shippingRates" required>
                <option value="0" @if(!empty($method->options->shippingRates) && $method->options->shippingRates==0) selected @endif>ONLINE</option>
                <option value="1" @if(!empty($method->options->shippingRates) && $method->options->shippingRates==1) selected @endif>ALL</option>
            </select>
        </div>

        <div class="form-group">
            <label for="machinable">* {{trans('icommerceusps::icommerceusps.table.machinable')}}</label>
            <select class="form-control" id="machinable" name="machinable" required>
                <option value="0" @if(!empty($method->options->machinable) && $method->options->machinable==0) selected @endif>NO</option>
                <option value="1" @if(!empty($method->options->machinable) && $method->options->machinable==1) selected @endif>YES</option>
            </select>
        </div>

        <div class="form-group">
            <div>
                <label class="checkbox-inline">
                    <input name="status" type="checkbox" @if($method->status==1) checked @endif>{{trans('icommerce::paymentmethods.table.activate')}}
                </label>
            </div>   
        </div>

        <div class="infor" style="margin-top: 5px;margin-bottom: 15px;">
            <span class="label label-info">INFO</span>
            <ul>
                <li>Weight is in Pounds or Ounces (Not editable)</li>
            </ul>
        </div>

    </div>
    </div>

</div>


<div class="clearfix"></div>   

<div class="box-footer">
    <button type="submit" class="btn btn-primary btn-flat">{{ trans('icommerce::paymentmethods.button.save configuration') }} {{$method->title}}</button>
</div>


{!! Form::close() !!}