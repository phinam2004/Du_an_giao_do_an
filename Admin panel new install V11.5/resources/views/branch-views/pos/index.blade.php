@extends('layouts.branch.app')
@section('title', translate('POS'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="d--none">
                    <div class="loading-inner">
                        <img width="200" src="{{asset('public/assets/admin/img/loader.gif')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

            <div class="container-fluid">
                <div class="row gy-3 gx-2">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="pos-title">
                                <h4 class="mb-0">{{translate('Product_Section')}}</h4>
                            </div>
                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between gap-3 gap-xl-4 px-4 py-4">
                                <div class="w-100 mr-xl-2">
                                    <select name="category" id="category" class="form-control js-select2-custom mx-1 category" title="{{translate('select category')}}">
                                        <option value="">{{translate('All Categories')}}</option>
                                        @foreach ($categories as $item)
                                        <option value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{ Str::limit($item->name, 40)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-100 ml-xl-2">
                                    <form id="search-form">
                                        <div class="input-group input-group-merge input-group-flush border rounded">
                                            <div class="input-group-prepend pl-2">
                                                <div class="input-group-text">
                                                    <img width="13" src="{{asset('public/assets/admin/img/icons/search.png')}}" alt="">
                                                </div>
                                            </div>
                                            <input id="datatableSearch" type="search" value="{{$keyword?$keyword:''}}" name="search" class="form-control border-0" placeholder="{{translate('Search_here')}}" aria-label="Search here">
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <div class="card-body pt-0" id="items">
                                <div class="pos-item-wrap justify-content-center">
                                    @foreach($products as $product)
                                        @include('branch-views.pos._single_product',['product'=>$product])
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-3 d-flex justify-content-end">
                                {!!$products->withQueryString()->links()!!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card billing-section-wrap">
                            <div class="pos-title">
                                <h4 class="mb-0">{{translate('Billing_Section')}}</h4>
                            </div>

                            <div class="p-2 p-sm-4">
                                <div class="d-flex flex-row gap-2 mb-3">
                                    <select id='customer' name="customer_id" data-placeholder="{{translate('Walk_In_Customer')}}" class="js-data-example-ajax form-control customer">
                                        <option disabled selected>{{translate('select Customer')}}</option>
                                        @foreach(\App\User::select('id', 'f_name', 'l_name')->get() as $customer)
                                            <option value="{{$customer['id']}}" {{ session()->get('customer_id') == $customer['id'] ? 'selected' : '' }}>{{$customer['f_name']. ' '. $customer['l_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="{{translate('Add Customer')}}">
                                        <i class="tio-add"></i>
                                        {{translate('Customer')}}
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label class="input-label font-weight-semibold fz-16 text-dark">{{translate('Select Order Type')}}</label>
                                    <div>
                                        <div class="form-control d-flex flex-column-3">
                                            <label class="custom-radio d-flex gap-2 align-items-center m-0">
                                                <input type="radio" class="order-type-radio" name="order_type" value="take_away" {{ !session()->has('order_type') || session()->get('order_type') == 'take_away' ? 'checked' : '' }}>
                                                <span class="media align-items-center mb-0">
                                                    <span class="media-body">{{translate('Take Away')}}</span>
                                                </span>
                                            </label>

                                            <label class="custom-radio d-flex gap-2 align-items-center m-0">
                                                <input type="radio" class="order-type-radio" name="order_type" value="dine_in" {{ session()->has('order_type') && session()->get('order_type') == 'dine_in' ? 'checked' : '' }}>
                                                <span class="media align-items-center mb-0">
                                                    <span class="media-body">{{translate('Dine-In')}}</span>
                                                </span>
                                            </label>

                                            <label class="custom-radio d-flex gap-2 align-items-center m-0">
                                                <input type="radio" class="order-type-radio" name="order_type" value="home_delivery" {{ session()->has('order_type') && session()->get('order_type') == 'home_delivery' ? 'checked' : '' }}>
                                                <span class="media align-items-center mb-0">
                                                    <span class="media-body">{{translate('Home Delivery')}}</span>
                                                </span>
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <div class="d-none" id="dine_in_section">
                                    <div class="form-group d-flex flex-wrap flex-sm-nowrap gap-2">
                                        <select id='table' name="table_id"  class="table-data-selector form-control form-ellipsis select-table">
                                            <option selected disabled>{{translate('Select Table')}}</option>
                                            @foreach($tables as $table)
                                                <option value="{{$table['id']}}" {{ $table['id'] == session('table_id') ? 'selected' : ''}}>{{translate('Table')}} - {{$table['number']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group d-flex flex-wrap flex-sm-nowrap gap-2">
                                        <input type="number" value="{{ session('people_number') }}" name="number_of_people"
                                               oninput="this.value = this.value.replace(/[^\d]/g, '')"
                                               onkeyup="store_key('people_number',this.value)" id="number_of_people"
                                               class="form-control" id="password" min="1" max="99"
                                               placeholder="{{translate('Number Of People')}}">
                                    </div>
                                </div>

                                <div class="form-group d-none" id="home_delivery_section">
                                    <div class="d-flex justify-content-between">
                                        <label for="" class="font-weight-semibold fz-16 text-dark">{{translate('Delivery Information')}}
                                            <small>({{ translate('Home Delivery') }})</small>
                                        </label>
                                        <span class="edit-btn cursor-pointer" id="delivery_address" data-toggle="modal"
                                              data-target="#AddressModal"><i class="tio-edit"></i>
                                        </span>
                                    </div>
                                    <div class="pos--delivery-options-info d-flex flex-wrap" id="del-add">
                                        @include('branch-views.pos._address')
                                    </div>
                                </div>

                                <div class='w-100' id="cart">
                                    @include('branch-views.pos._cart')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- container //  -->

        <!-- End Content -->
        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>

        <div class="modal fade" id="add-customer" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('Add_New_Customer')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('branch.pos.customer-store')}}" method="post">
                            @csrf
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('First_Name')}}
                                            <span class="input-label-secondary text-danger">*</span>
                                        </label>
                                        <input type="text" name="f_name" class="form-control" value="" placeholder="{{translate('First_Name')}}" required="">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('Last_Name')}}
                                            <span class="input-label-secondary text-danger">*</span>
                                        </label>
                                        <input type="text" name="l_name" class="form-control" value="" placeholder="{{translate('Last_Name')}}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('Email')}}
                                            <span class="input-label-secondary text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control" value="" placeholder="{{translate('Ex : ex@example.com')}}" required="">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('Phone')}}
                                            ({{translate('with_country_code')}})
                                            <span class="input-label-secondary text-danger">*</span>
                                        </label>
                                        <input type="text" name="phone" class="form-control" value="" placeholder="{{translate('Phone')}}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-secondary mr-1">{{translate('reset')}}</button>
                                <button type="submit" id="submit_new_customer" class="btn btn-primary">{{translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @php($order=\App\Model\Order::find(session('last_order')))
        @if($order)
        @php(session(['last_order'=> false]))
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <div class="w-100 d-flex justify-content-between align-items-center gap-3">
                            <h5 class="modal-title">{{translate('Print Invoice')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="mt-4">
                            <center>
                                <input type="button" class="btn btn-primary non-printable print-button"
                                    value="{{translate('Proceed, If thermal printer is ready.')}}"/>
                                <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{translate('Back')}}</a>
                            </center>
                            <hr class="non-printable">
                        </div>
                    </div>
                    <div class="modal-body row custom-modal-body overflow-auto pt-0">
                        <div class="row custom-print-area-auto" id="printableArea">
                            @include('branch-views.pos.order.invoice')
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif

    <div class="modal fade" id="AddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light border-bottom py-3">
                    <h5 class="modal-title flex-grow-1 text-center">{{ translate('Delivery Information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?php
                    if(session()->has('address')) {
                        $old = session()->get('address');
                    }else {
                        $old = null;
                    }
                    ?>
                    <form id='delivery_address_store'>
                        @csrf

                        <div class="row g-2" id="delivery_address">
                            <div class="col-md-6">
                                <label class="input-label" for="">{{ translate('contact_person_name') }}
                                    <span class="input-label-secondary text-danger">*</span></label>
                                <input type="text" class="form-control" name="contact_person_name"
                                       value="{{ $old ? $old['contact_person_name'] : '' }}" placeholder="{{ translate('Ex :') }} Jhon" required>
                            </div>
                            <div class="col-md-6">
                                <label class="input-label" for="">{{ translate('Contact Number') }}
                                    <span class="input-label-secondary text-danger">*</span></label>
                                <input type="tel" class="form-control" name="contact_person_number"
                                       value="{{ $old ? $old['contact_person_number'] : '' }}"  placeholder="{{ translate('Ex :') }} +3264124565" required>
                            </div>
                            <div class="col-md-4">
                                <label class="input-label" for="">{{ translate('Road') }}</label>
                                <input type="text" class="form-control" name="road" value="{{ $old ? $old['road'] : '' }}"  placeholder="{{ translate('Ex :') }} 4th">
                            </div>
                            <div class="col-md-4">
                                <label class="input-label" for="">{{ translate('House') }}</label>
                                <input type="text" class="form-control" name="house" value="{{ $old ? $old['house'] : '' }}" placeholder="{{ translate('Ex :') }} 45/C">
                            </div>
                            <div class="col-md-4">
                                <label class="input-label" for="">{{ translate('Floor') }}</label>
                                <input type="text" class="form-control" name="floor" value="{{ $old ? $old['floor'] : '' }}"  placeholder="{{ translate('Ex :') }} 1A">
                            </div>
                            <div class="col-md-12">
                                <label class="input-label" for="">{{ translate('address') }}</label>
                                <textarea name="address" id="address" class="form-control" cols="30" rows="3" placeholder="{{ translate('Ex :') }} address" required>{{ $old ? $old['address'] : '' }}</textarea>
                            </div>

                            <?php
                                $branchId =(int) auth('branch')->id() ?? 1;
                                $branch = \App\Model\Branch::with(['delivery_charge_setup', 'delivery_charge_by_area'])
                                    ->where(['id' => $branchId])
                                    ->first(['id', 'name', 'status']);

                                $deliveryType = $branch->delivery_charge_setup->delivery_charge_type ?? 'fixed';
                                $deliveryType = $deliveryType == 'area' ? 'area' : ($deliveryType == 'distance' ? 'distance' : 'fixed');

                                if (isset($branch->delivery_charge_setup) && $branch->delivery_charge_setup->delivery_charge_type == 'distance') {
                                    unset($branch->delivery_charge_by_area);
                                    $branch->delivery_charge_by_area = [];
                                }
                            ?>

                            @php($googleMapStatus = \App\CentralLogics\Helpers::get_business_settings('google_map_status'))
                            @if($googleMapStatus)
                                @if($deliveryType == 'distance')
                                    <div class="col-md-6">
                                        <label class="input-label" for="">{{ translate('longitude') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" id="longitude" name="longitude"
                                               value="{{ $old ? $old['longitude'] : '' }}" readonly required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="input-label" for="">{{ translate('latitude') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" id="latitude" name="latitude"
                                               value="{{ $old ? $old['latitude'] : '' }}" readonly required>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                        <span class="text-primary">
                                            {{ translate('* pin the address in the map to calculate delivery fee') }}
                                        </span>
                                        </div>
                                        <div id="location_map_div">
                                            <input id="pac-input" class="controls rounded initial-8"
                                                   title="{{ translate('search_your_location_here') }}" type="text"
                                                   placeholder="{{ translate('search_here') }}" />
                                            <div id="location_map_canvas" class="overflow-hidden rounded custom-height"></div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if($deliveryType == 'area')
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Delivery Area') }}</label>
                                    <select name="selected_area_id" class="form-control js-select2-custom-x mx-1" id="areaDropdown" >
                                        <option value="">{{ translate('Select Area') }}</option>
                                        @foreach($branch->delivery_charge_by_area as $area)
                                            <option value="{{$area['id']}}" {{ (isset($old) && $old['area_id'] == $area['id']) ? 'selected' : '' }} data-charge="{{$area['delivery_charge']}}" >{{ $area['area_name'] }} - ({{ Helpers::set_symbol($area['delivery_charge']) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label" for="">{{ translate('Delivery Charge') }}</label>
                                    <input type="number" class="form-control" name="delivery_charge" id="deliveryChargeInput" value="" readonly>
                                </div>
                            @endif

                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="btn--container justify-content-end">
                                <button class="btn btn-sm btn-primary w-100 delivery-address-update-button" type="button" data-dismiss="modal">
                                    {{  translate('Update') }} {{ translate('Delivery address') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&v=3.51"></script>

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<script>
    "use strict";

    $('.print-button').click(function() {
        printDiv('printableArea');
    });

    $('.quick-view-trigger').click(function() {
        var productId = $(this).data('product-id');
        quickView(productId);
    });

    $('.category').change(function() {
        var selectedCategory = $(this).val();
        set_category_filter(selectedCategory);
    });

    $('.customer').change(function() {
        var selectedCustomerId = $(this).val();
        store_key('customer_id', selectedCustomerId);
    });

    $('.order-type-radio').change(function() {
        var selectedOrderType = $(this).val();
        select_order_type(selectedOrderType);
    });

    $('.select-table').change(function() {
        var selectedTableId = $(this).val();
        store_key('table_id', selectedTableId);
    });

    $('.delivery-address-update-button').click(function() {
        deliveryAdressStore();
    });

    $(document).on('ready', function () {
        @if($order)
        $('#print-invoice').modal('show');
        @endif
    });

    function printDiv(divName) {

        if($('html').attr('dir') === 'rtl') {
            $('html').attr('dir', 'ltr')
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            $('#printableAreaContent').attr('dir', 'rtl')
            window.print();
            document.body.innerHTML = originalContents;
            $('html').attr('dir', 'rtl')
            location.reload();
        }else{
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

    }

    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }


    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword= $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function store_key(key, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('branch.pos.store-keys')}}',
            data: {
                key:key,
                value:value,
            },
            success: function (data) {
                console.log(data);
                var selected_field_text = key;
                var selected_field = selected_field_text.replace("_", " ");
                var selected_field = selected_field.replace("id", " ");
                var message = selected_field+' '+'selected!';
                var new_message = message.charAt(0).toUpperCase() + message.slice(1);
                toastr.success((new_message), {
                    CloseButton: true,
                    ProgressBar: true
                });
                if (data === 'table_id') {
                    $('#pay_after_eating_li').css('display', 'block')
                }
            },
        });
    }

    function addon_quantity_input_toggle(e)
    {
        var cb = $(e.target);
        if(cb.is(":checked"))
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
        }
        else
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
        }
    }
    function quickView(product_id) {
        $.ajax({
            url: '{{route('branch.pos.quick-view')}}',
            type: 'GET',
            data: {
                product_id: product_id
            },
            dataType: 'json',
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });

    }

    function checkAddToCartValidity() {
        return true;
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var stock_type = $(this).attr('data-stock_type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());
            var minVal = parseInt(input.attr('min'));
            var maxVal = parseInt(input.attr('max'));

            if (!isNaN(currentVal)) {
                if (type === 'minus') {
                    if (currentVal > minVal) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) <= minVal) {
                        $(this).attr('disabled', true);
                    }

                    // Enable plus button when minus clicked
                    $(".btn-number[data-type='plus'][data-field='" + fieldName + "']").removeAttr('disabled');
                }
                else if (type === 'plus') {
                    if (stock_type === 'unlimited' || currentVal < maxVal) {
                        input.val(currentVal + 1).change();
                        $(".btn-number[data-type='minus'][data-field='" + fieldName + "']").removeAttr('disabled');
                    }

                    if (stock_type !== 'unlimited' && currentVal + 1 >= maxVal + 1) {
                        $(this).attr('disabled', true);

                        Swal.fire({
                            icon: 'warning',
                            title: '{{ translate("Cart") }}',
                            text: '{{ translate("You have reached the maximum available stock.") }}',
                            confirmButtonText: '{{ translate("OK") }}'
                        });
                    }
                }
            } else {
                input.val(1);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            var minValue = parseInt($(this).attr('min'));
            var maxValue = parseInt($(this).attr('max'));
            var stock_type = $("button[data-field='" + $(this).attr('name') + "']").data('stock_type');
            var valueCurrent = parseInt($(this).val());
            var name = $(this).attr('name');

            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title:'{{translate("Cart")}}',
                    text: '{{translate('Sorry, the minimum value was reached')}}'
                });
                $(this).val($(this).data('oldValue'));
            }

            if (valueCurrent < minValue) {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate("Sorry, the minimum value was reached")}}'
                });
                $(this).val($(this).data('oldValue'));
                return;
            }

            if (stock_type !== 'unlimited' && valueCurrent > maxValue) {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    confirmButtonText: '{{translate("Ok")}}',
                    text: '{{translate("Sorry, stock limit exceeded")}}.'
                });
                $(this).val($(this).data('oldValue'));
                return;
            }

            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled');
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled');
        });


        $(".input-number").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                (e.keyCode == 65 && e.ctrlKey === true) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('branch.pos.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    if(data.error == 'quantity_error'){
                        toastr.error(data.message);
                    }
                    else{
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    }
                }
            });
        }
    }

    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('branch.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log(data)
                    if (data.data == 1) {
                        Swal.fire({
                            confirmButtonColor: '#FC6A57',
                            icon: 'info',
                            title: '{{translate("Cart")}}',
                            confirmButtonText:'{{translate("Ok")}}',
                            text: "{{translate('Product already added in cart')}}"
                        });
                        return false;
                    } else if (data.data == 0) {
                        Swal.fire({
                            confirmButtonColor: '#FC6A57',
                            icon: 'error',
                            title: '{{translate("Cart")}}',
                            confirmButtonText:'{{translate("Ok")}}',
                            text: '{{translate('Sorry, product out of stock')}}.'
                        });
                        return false;
                    } else if (data.data == 'variation_error') {
                        Swal.fire({
                            confirmButtonColor: '#FC6A57',
                            icon: 'error',
                            title: 'Cart',
                            text: data.message
                        });
                        return false;
                    }
                    else if (data.data == 'stock_limit') {
                        Swal.fire({
                            confirmButtonColor: '#FC6A57',
                            icon: 'error',
                            title: 'Cart',
                            text: data.message
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{translate('Item has been added in your cart')}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateCart();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire({
                confirmButtonColor: '#FC6A57',
                type: 'info',
                title: '{{translate("Cart")}}',
                confirmButtonText:'{{translate("Ok")}}',
                text: '{{translate('Please choose all the options')}}'
            });
        }
    }

    function removeFromCart(key) {
        $.post('{{ route('branch.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                updateCart();
                toastr.info('{{translate('Item has been removed from cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

        });
    }

    function emptyCart() {
        $.post('{{ route('branch.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
            updateCart();
            toastr.info('{{translate('Item has been removed from cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        });
    }

    function updateCart() {
        $.post('<?php echo e(route('branch.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
        });
    }

   $(function(){
        $(document).on('click','input[type=number]',function(){ this.select(); });
    });


    function updateQuantity(e){
        var element = $( e.target );
        var minValue = parseInt(element.attr('min'));
        var valueCurrent = parseInt(element.val());

        var key = element.data('key');
        if (valueCurrent >= minValue) {
            $.post('{{ route('branch.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:valueCurrent}, function (data) {
                updateCart();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{translate("Cart")}}',
                confirmButtonText:'{{translate("Ok")}}',
                text: '{{translate('Sorry, the minimum value was reached')}}'
            });
            element.val(element.data('oldValue'));
        }
        if(e.type == 'keydown')
        {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                (e.keyCode == 65 && e.ctrlKey === true) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    };

    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{route('branch.pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $('#order_place').submit(function(eventObj) {
        if($('#customer').val())
        {
            $(this).append('<input type="hidden" name="user_id" value="'+$('#customer').val()+'" /> ');
        }
        return true;
    });

    $(document).ready(function() {
        var orderType = {!! json_encode(session('order_type')) !!};

        if (orderType === 'dine_in') {
            $('#dine_in_section').removeClass('d-none');
        } else if (orderType === 'home_delivery') {
            $('#home_delivery_section').removeClass('d-none');
            $('#dine_in_section').addClass('d-none');
        } else {
            $('#home_delivery_section').addClass('d-none');
            $('#dine_in_section').addClass('d-none');
        }
    });

    function select_order_type(order_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('branch.pos.order_type.store')}}',
            data: {
                order_type:order_type,
            },
            success: function (data) {
                updateCart();
            },
        });

        if (order_type == 'dine_in') {
            $('#dine_in_section').removeClass('d-none');
            $('#home_delivery_section').addClass('d-none')
        } else if(order_type == 'home_delivery') {
            $('#home_delivery_section').removeClass('d-none');
            $('#dine_in_section').addClass('d-none');
        }else{
            $('#home_delivery_section').addClass('d-none')
            $('#dine_in_section').addClass('d-none');
        }
    }

    // Update paid-by radio button handler
    $('.paid-by').change(function() {
        var selectedPaymentOption = $(this).val();

        // Get total order amount from the displayed value
        var totalOrderAmount = $('.hidden-paid-amount').val();

        // Toggle collect cash section visibility
        if (selectedPaymentOption == 'pay_after_eating') {
            $('.collect-cash-section').addClass('d-none');
        } else {
            $('.collect-cash-section').removeClass('d-none');
        }

        // Toggle readonly attribute for paid amount input
        if (selectedPaymentOption == 'card') {
            $('#paid-amount').attr('readonly', true);
            $('#paid-amount').addClass('bg--1');
            // Reset paid amount to order amount
            $('#paid-amount').val(totalOrderAmount);
            calculateAmountDifference();
        } else {
            $('#paid-amount').removeAttr('readonly');
            $('#paid-amount').removeClass('bg--1');
        }
    });

    $( document ).ready(function() {
        function initAutocomplete() {
            var myLatLng = {

                lat: 23.811842872190343,
                lng: 90.356331
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: 23.811842872190343,
                    lng: 90.356331
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                        }
                    }
                });
            });
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function(event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        initAutocomplete();
    });

    function deliveryAdressStore(form_id = 'delivery_address_store') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: '{{ route('branch.pos.add-delivery-address') }}',
            data: $('#' + form_id).serializeArray(),
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    $('#del-add').empty().html(data.view);
                }
                updateCart();
                $('.call-when-done').click();
            },
            complete: function() {
                $('#loading').hide();
            }
        });
    }

    $(document).ready(function() {
        const $areaDropdown = $('#areaDropdown');
        const $deliveryChargeInput = $('#deliveryChargeInput');

        $areaDropdown.change(function() {
            const selectedOption = $(this).find('option:selected');
            const charge = selectedOption.data('charge');
            $deliveryChargeInput.val(charge);
        });
    });
</script>
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
@endpush

