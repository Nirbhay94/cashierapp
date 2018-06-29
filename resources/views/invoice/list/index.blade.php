@extends('layouts.master')
@section('page_title', __('Lists'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('INVOICE')}}
			<a href="javascript:void(0);" class="btn new-record bg-purple pull-right">
				{{__('CREATE')}}
			</a>
			<small class="clearfix">
				{{__('List')}}
			</small>
		</h2>
	</div>
	
	<div class="callout-block callout-info">
		<div class="icon-holder">
			<i class="fa fa-info-circle"></i>
		</div>
		<div class="content">
			<h4 class="callout-title">{{__('Customer Notifications')}}</h4>
			<p>{{__('The system will automatically send invoice notifications to user by default. Ensure that customers have an email on their record in order to receive notifications.')}}</p>
		</div>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<div class="box-title">
						<h2>
							{{__('INVOICES')}}
							<small>{{__('List of customers issued invoice')}}</small>
						</h2>
					</div>
					<ul class="header-dropdown m-r--5">
						<li>
							<a href="javascript:void(0);" class="refresh-toggle">
								<i class="material-icons">loop</i>
							</a>
						</li>
					</ul>
				</div>
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="table_data">
							<thead>
							<tr>
								<th class="all">{{__('ID')}}</th>
								<th class="none">{{__('Token')}}</th>
								<th>{{__('Customer')}}</th>
								<th>{{__('Note')}}</th>
								<th>{{__('Allow Partial')}}</th>
								<th>{{__('Sub Total')}}</th>
								<th>{{__('Tax')}}</th>
								<th>{{__('Total')}}</th>
								<th>{{__('Status')}}</th>
								<th class="none">{{__('Transaction Id')}}</th>
								<th class="none">{{__('Processor')}}</th>
								<th class="none">{{__('Amount Paid')}}</th>
								<th class="none">{{__('Repeat Status')}}</th>
								<th class="none">{{__('Items')}}</th>
								<th class="all">{{__('Actions')}}</th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th class="all">{{__('ID')}}</th>
								<th class="none">{{__('Token')}}</th>
								<th>{{__('Customer')}}</th>
								<th>{{__('Note')}}</th>
								<th>{{__('Allow Partial')}}</th>
								<th>{{__('Sub Total')}}</th>
								<th>{{__('Tax')}}</th>
								<th>{{__('Total')}}</th>
								<th>{{__('Status')}}</th>
								<th class="none">{{__('Transaction Id')}}</th>
								<th class="none">{{__('Processor')}}</th>
								<th class="none">{{__('Amount Paid')}}</th>
								<th class="none">{{__('Repeat Status')}}</th>
								<th class="none">{{__('Items')}}</th>
								<th class="all">{{__('Actions')}}</th>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="alter-row" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form id="modal-form">
					<div class="modal-header">
						<h4 class="modal-title" id="modal-label"></h4>
					</div>
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-md-12">
								<div class="form-group row">
									{!! Form::label('customer_id', __('Customer'), array('class' => 'col-md-3')); !!}
									<div class="form-float col-md-9">
										<div class="form-line {{ $errors->has('customer_id') ? ' error ' : '' }}">
											{!! Form::select('customer_id', [], null, array('id' => 'customer_id', 'class' => 'form-control customer_id ms', 'placeholder' => __('Select Customer'), 'required')) !!}
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('note', __('Note'), array('class' => 'col-md-3')); !!}
									<div class="form-float col-md-9">
										<div class="form-line {{ $errors->has('note') ? ' error ' : '' }}">
											{!! Form::text('note', null, array('id' => 'note', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Short note on invoice...'))) !!}
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('apply_balance', __('Apply Balance'), array('class' => 'col-md-3')); !!}
									<div class="form-float col-md-9">
										<div class="form-line {{ $errors->has('apply_balance') ? ' error ' : '' }}">
											@php $options = array_combine_single(['yes', 'no']) @endphp
											{!! Form::select('apply_balance', $options, null, array('id' => 'apply_balance', 'class' => 'form-control', 'placeholder' => __('Apply customers balance?'), 'required') ) !!}
										</div>
										<span class="help-block">{{__('Please note that customers balance will be deducted!')}}</span>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('allow_partial', __('Allow Partial'), array('class' => 'col-md-3')); !!}
									<div class="form-float col-md-9">
										<div class="form-line {{ $errors->has('allow_partial') ? ' error ' : '' }}">
											@php $options = array_combine_single(['yes', 'no']) @endphp
											{!! Form::select('allow_partial', $options, null, array('id' => 'allow_partial', 'class' => 'form-control', 'placeholder' => __('Support Partial Payment?'), 'required') ) !!}
										</div>
									</div>
								</div>
								
								<div class="clearfix repeater m-b-30 row">
									<div data-repeater-list="products" class="col-md-12">
										<div data-repeater-item class="form-group row">
											<div class="form-float col-sm-5">
												<div class="form-line {{ $errors->has('product_id') ? ' error ' : '' }}">
													{!! Form::select('product_id', [], null, array('class' => 'form-control product_id ms', 'placeholder' => __('Select Product'), 'required') ) !!}
												</div>
											</div>
											<div class="form-float col-sm-5">
												<div class="form-line {{ $errors->has('quantity') ? ' error ' : '' }}">
													{!! Form::number('quantity', null, array('class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Quantity'))) !!}
												</div>
											</div>
											<div class="col-sm-2 align-right">
												<a href="javascript:void(0)" class="material-icons col-red" data-repeater-delete>clear</a>
											</div>
										</div>
									</div>
									<div class="row clearfix">
										<div class="col-md-9 col-md-offset-3">
											<input data-repeater-create type="button" class="btn btn-primary" value="{{__('Add Products')}}"/>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row" role="tab" id="repeat-header">
							<label class="col-xs-8">{{__('Schedule Repeat')}}</label>
							<div class="switch col-xs-4">
								<label>
									<input type="checkbox"  name="enable_repeat" value="yes">
									
									<span class="lever switch-col-purple"></span>
								</label>
							</div>
						</div>
						
						<div id="repeat-body" class="panel-collapse collapse" role="tabpanel" aria-labelledby="repeat-header">
							<div class="panel-body">
								<div class="row clearfix">
									<div class="col-md-4">
										<div class="form-group" style="margin: 0;">
											{!! Form::label('repeat_type', __('Select Type')) !!}
											@php $options = array_combine_single(['daily', 'weekly', 'monthly', 'yearly']);@endphp
											{!! Form::select('repeat_type', $options, null, ['class' => 'form-control select2', 'id' => 'repeat_type']) !!}
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group" style="margin: 0;">
											<label for="repeat_interval" class="control-label">{{__('Interval')}}</label>
											<div class="form-line">
												{!! Form::text('repeat_interval', null, ['class' => 'form-control', 'id' => 'repeat_interval']) !!}
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label for="repeat_until" class="control-label">{{__('Until')}}</label>
										<div class="input-group" style="margin: 0;">
											<div class="form-line">
												{!! Form::text('repeat_until', null, ['class' => 'form-control datetimepicker', 'id' => 'repeat_until']) !!}
											</div>
											<span class="input-group-addon">
	                                            <i class="material-icons">date_range</i>
	                                        </span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="form-submit" class="btn bg-purple waves-effect">{{__('SUBMIT')}}</button>
						<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{__('CLOSE')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('js')
	<script>
        var Page = function(){
            var dataTable, formModalElement = $('#alter-row');

            var handleDatetimePicker = function(){
                $('.datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
            };

            var handleNewRecord = function(){
                var label = formModalElement.find('#modal-label');
                var form = formModalElement.find('#modal-form');
                var formAction = window.location.href;

                $('.new-record').click(function(){
                    label.html('{{__('Add Record')}}');

                    Global.resetForm(form);
                    Global.prepareForm(form);
                    
                    formModalElement.modal('show');
	                
                    Global.bindModalAjaxForm(form, formAction, 'POST', dataTable);

                });
            };

            var handleRepeatToggle = function(){
                $('input[name="enable_repeat"]').change(function(){
                    if($(this).is(':checked')){
                        $('#repeat-body').collapse('show');
                    }else{
                        $('#repeat-body').collapse('hide');
                    }
                });
            };
            
            var handleFormRepeater = function(){
                $('.repeater').repeater({
                    initEmpty: false,

                    show: function () {
                        if($(this).slideDown()){
                            Page.initProductSelect();
                        }
                    },

                    hide: function (deleteElement) {
                        if(confirm('Are you sure?')) {
                            $(this).slideUp(deleteElement);
                        }
                    },

                    isFirstItemUndeletable: true
                })
            };
            
            var handleDatatable = function () {
                var exportable = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

                dataTable = $('#table_data')
                    .on('processing.dt', function (e, settings, processing) {
                        var card = $(this).closest('.card');

                        if(processing){
                            card.waitMe({
                                effect: 'rotation',
                                text: '{{__('Loading')}}...',
                                bg: 'rgba(255,255,255,0.50)',
                                color: ['#555', '#9C27B0']
                            });
                        }else{
                            card.waitMe('hide');
                        }
                    }).DataTable({
                        processing: false,
                        serverSide: true,
                        "ajax": {
                            "async": true,
                            "type": "POST",
                            "url": '{{route('invoice.list.data')}}',
                            "data": {
                                customer: '{{request()->get('customer')}}'
                            }
                        },
                        rowId: 'id',

                        dom: "<'row'<'col-md-4 col-sm-6 hidden-xs'l><'col-md-4 hidden-sm hidden-xs'B><'col-md-4 col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",

                        buttons: [
                            {extend: 'csv', exportOptions: {columns: exportable}},
                            {extend: 'print', exportOptions: {columns: exportable, stripHtml: false}},
                            {extend: 'excel', exportOptions: {columns: exportable}},
                        ],

                        columns: [
                            {data: 'id'},
                            {data: 'token'},
                            {data: 'customer_id'},
                            {data: 'note'},
                            {data: 'allow_partial'},
                            {data: 'sub_total'},
                            {data: 'tax'},
                            {data: 'total'},
                            {data: 'status'},
                            {data: 'transaction_id'},
                            {data: 'payment_processor'},
                            {data: 'amount_paid'},
                            {data: 'repeat_data'},
                            {data: 'items', orderable: false},
                            {data: 'action', orderable: false}
                        ],

                        "language": {
                            "aria": {
                                "sortAscending": ": {{__('activate to sort column ascending')}}",
                                "sortDescending": ": {{__('activate to sort column descending')}}"
                            },
                            "emptyTable": "{{__('No data available in table')}}",
                            "info": "{{__('Showing').' _START_ '.__('to').' _END_ '.__('of').' _TOTAL_ '.__('entries')}}",
                            "infoEmpty": "{{__('No entries found')}}",
                            "infoFiltered": "{{'(filtered1 '.__('from').' _MAX_ '.__('total entries').')'}}",
                            "lengthMenu": "{{'_MENU_ '.__('entries')}}",
                            "search": "{{__('Search:')}}",
                            "zeroRecords": "{{__('No matching records found')}}"
                        },

                        responsive: true,

                        "order": [
                            [0, 'desc']
                        ],

                        "lengthMenu": [
                            [20, 50, 100, 500, -1],
                            [20, 50, 100, 500, "All"] // change per page values here
                        ],

                        "pageLength": 50,
                    });

                dataTable.on('draw.dt', function () {
                    //Page.listenRowEdit();
                    Page.listenRowDelete();
                    Page.listenPayInvoice();
                });

                $('.refresh-toggle').click(function(){
                    dataTable.ajax.reload();
                });
            };

            return {
                init: function(){
                    handleDatetimePicker();
                    handleFormRepeater();
                    handleNewRecord();
                    handleRepeatToggle();
                    handleDatatable();

                    formModalElement.on('shown.bs.modal', function (e) {
                        Page.initProductSelect();
                        Page.initCustomerSelect();
                    });
                },
	            
	            initProductSelect: function(){
                    $('.product_id').select2({
                        minimumInputLength: 2,

                        ajax: {
                            url: '{{route('products.all.ajax.search')}}',
                            dataType: 'json',

                            data: function (params) {
                                return {
                                    q: $.trim(params.term)
                                };
                            },

                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },

                            cache: true
                        }
                    });
                },
	            
	            initCustomerSelect: function(){
                    $('.customer_id').select2({
                        minimumInputLength: 2,

                        ajax: {
                            url: '{{route('customers.list.ajax.search')}}',
                            dataType: 'json',

                            data: function (params) {
                                return {
                                    q: $.trim(params.term)
                                };
                            },

                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },

                            cache: true
                        }
                    });
	            },
	            
                listenRowEdit: function() {
                    var id, formAction, input, name;
                    var label = formModalElement.find('#modal-label');
                    var form = formModalElement.find('#modal-form');

                    $('.edit-row').click(function(e){
                        e.preventDefault();

                        if(id = $(this).data('id')){
                            label.html('{{__('Edit Record')}}');
                            formAction = $(this).attr('href');

                            $.each(dataTable.row('#'+id).data(), function(key, value){
                                value = value ? value : '';

                                if(input = form.find(':input[name="' + key + '"]')){
                                    $.each(input, function(aKey, aValue){
                                        $(aValue).val(value);
                                    });
                                }

                                if(input = form.find(':input[name="' + key + '[]"]')){
                                    var multipleValues = value.split(",");

                                    $.each(input, function(aKey, aValue){
                                        if(!$(aValue).is('select')){
                                            if(!$(aValue).is('input[type="text"], input[type="hidden"], textarea')){
                                                if($.inArray($(aValue).val(), multipleValues)){
                                                    $(aValue).prop("checked", true);
                                                }else{
                                                    $(aValue).prop("checked", false);
                                                }
                                            }else{
                                                $(aValue).val(value);
                                            }
                                        }else{
                                            $(aValue).find('option:selected').prop("selected", false);

                                            $.each(multipleValues, function(bKey, bValue){
                                                $(aValue).find('option[value="' + bValue + '"]').prop("selected", true);
                                            });
                                        }
                                    });
                                }
                            });
	                        
                            Global.prepareForm(form);
                            formModalElement.modal('show');
	                        
                            Global.bindModalAjaxForm(form, formAction, 'PUT', dataTable);
                        }

                        return false;
                    });
                },

                listenRowDelete: function () {
                    var id, link;

                    $('.delete-row').click(function(e){
                        e.preventDefault();

                        if(id = $(this).data('id')){
                            link = $(this).attr('href');

                            swal({
                                title: "{{__('Are you sure?')}}",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "{{__('Yes, remove it!')}}",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true
                            }, function () {
                                $.ajax({
                                    url: link,
                                    method: 'DELETE',
                                    success: function(response) {
                                        var title = "{{__('Successful')}}";

                                        swal(title, response, "success");

                                        dataTable.ajax.reload();
                                    },
                                    error: function(xhr){
                                        swal.close();

                                        var response = xhr.responseJSON;

                                        if($.isPlainObject(response)){
                                            $.each(response.errors, function(key, value){
                                                Global.notifyDanger(value[0]);
                                            });
                                        }else{
                                            Global.notifyDanger(response);
                                        }
                                    }
                                });
                            });
                        }

                        return false;
                    });
                },
	            
	            listenPayInvoice: function () {
                    var id, link;

                    $('.pay-invoice').click(function(e){
                        e.preventDefault();

                        if(id = $(this).data('id')){
                            link = $(this).attr('href');

                            swal({
                                title: "{{__('This will deduct customer balance!')}}",
	                            text: "{{__('Changes may not reflect if the invoice does not support partial payment and customer has less balance.')}}",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#337ab7",
                                confirmButtonText: "{{__('Proceed!')}}",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true
                            }, function () {
                                $.ajax({
                                    url: link,
                                    method: 'POST',
                                    success: function(response) {
                                        var title = "{{__('Successful')}}";

                                        swal(title, response, "success");

                                        dataTable.ajax.reload();
                                    },
                                    error: function(xhr){
                                        swal.close();

                                        var response = xhr.responseJSON;

                                        if($.isPlainObject(response)){
                                            $.each(response.errors, function(key, value){
                                                Global.notifyDanger(value[0]);
                                            });
                                        }else{
                                            Global.notifyDanger(response);
                                        }
                                    }
                                });
                            });
                        }

                        return false;
                    });
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush