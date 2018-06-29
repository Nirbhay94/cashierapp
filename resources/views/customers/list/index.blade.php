@extends('layouts.master')
@section('page_title', __('Customers'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('CUSTOMERS')}}
			<a href="javascript:void(0);" class="btn new-record bg-purple pull-right">
				{{__('ADD')}}
			</a>
			<small class="clearfix">
				{{__('List')}}
			</small>
		</h2>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<div class="box-title">
						<h2>
							{{__('CUSTOMERS')}}
							<small>{{__('List of customers and their transaction activities')}}</small>
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
								<th>{{__('Image')}}</th>
								<th>{{__('Name')}}</th>
								<th>{{__('Email')}}</th>
								<th>{{__('Phone')}}</th>
								<th class="none">{{__('Location')}}</th>
								<th class="none">{{__('City')}}</th>
								<th class="none">{{__('Zip')}}</th>
								<th class="none">{{__('Country')}}</th>
								<th class="none">{{__('Purchases')}}</th>
								<th class="none">{{__('Balance')}}</th>
								<th class="none">{{__('Invoices')}}</th>
								<th class="all">{{__('Actions')}}</th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th class="all">{{__('ID')}}</th>
								<th>{{__('Image')}}</th>
								<th>{{__('Name')}}</th>
								<th>{{__('Email')}}</th>
								<th>{{__('Phone')}}</th>
								<th class="none">{{__('Location')}}</th>
								<th class="none">{{__('City')}}</th>
								<th class="none">{{__('Zip')}}</th>
								<th class="none">{{__('Country')}}</th>
								<th class="none">{{__('Purchases')}}</th>
								<th class="none">{{__('Balance')}}</th>
								<th class="none">{{__('Invoices')}}</th>
								<th class="all">{{__('Actions')}}</th>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="alter-row" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form id="modal-form">
					<div class="modal-header">
						<h4 class="modal-title" id="modal-label"></h4>
					</div>
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-md-6">
								<div class="form-group row">
									{!! Form::label('name', __('Name'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('name') ? ' error ' : '' }}">
											{!! Form::text('name', null, array('id' => 'name', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Customers Name'), 'required')) !!}
										</div>
									</div>
								</div>
								
								<div class="file-thumbnail">
									<div class="file-thumbnail-body">
										<div class="file-thumbnail-list">
											<div class="file-thumbnail-placeholder">
												{{__('Attach Square Size Images')}}
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="file-thumbnail-footer">
										<a href="#" id="filemanager" class="item popup_selector">
											<i class="fa fa-plus"></i>
										</a>
										<div class="clearfix"></div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('email', __('Email'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('email') ? ' error ' : '' }}">
											{!! Form::email('email', null, array('id' => 'email', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Customers Email (if available)'))) !!}
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('phone_number', __('Phone'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('phone_number') ? ' error ' : '' }}">
											{!! Form::text('phone_number', null, array('id' => 'phone_number', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Customers Phone (if available)'))) !!}
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group row">
									{!! Form::label('location', __('Location'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('location') ? ' error ' : '' }}">
											{!! Form::textarea('location', null, array('id' => 'location', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Enter Location (optional)'), 'rows' => 3)) !!}
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('city', __('City'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('city') ? ' error ' : '' }}">
											{!! Form::text('city', null, array('id' => 'city', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Enter City (optional)'))) !!}
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('zip', __('Zip'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('zip') ? ' error ' : '' }}">
											{!! Form::text('zip', null, array('id' => 'zip', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Enter Zip (optional)'))) !!}
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									{!! Form::label('country', __('Country'), array('class' => 'col-md-12')); !!}
									<div class="form-float col-md-12">
										<div class="form-line {{ $errors->has('country') ? ' error ' : '' }}">
											{!! Form::text('country', null, array('id' => 'country', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Enter Country (optional)'))) !!}
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
        function processSelectedFile(path, field) {
            var prefix = '/{{collect((array) config('elfinder.dir'))->first()}}/';

            if(!path.startsWith(prefix)){
                path = encodeURI(prefix + path);
            }

            var container = $('#'+field).closest('.file-thumbnail');
            var list = container.find('.file-thumbnail-list');
            var placeholder = list.find('.file-thumbnail-placeholder');
            var thumbnail = Page.getThumbnailFromPath(path);

            if(thumbnail){
                placeholder.hide();

                var item = '<div class="item" style="background-image: url('+thumbnail+')">';
                item += '<input type="hidden" name="media[]" value="'+path+'">';
                item += '<button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
                item += '</div>';

                list.append(item);

                Page.initRemoveListener();
            }else{
                Global.notifyDanger('{{_('The file type is not supported!')}}')
            }
        }
	</script>
	
	<script>
        var Page = function(){
            var dataTable, formModalElement = $('#alter-row');

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

            var handlePopupSelector = function(){
                $('.popup_selector').on('click', function (e) {
                    e.preventDefault();

                    var id = $(this).attr('id');
                    var url = '/elfinder/popup/'+id;

                    $.colorbox({
                        href: url,
                        fastIframe: true,
                        iframe: true,
                        width: '90%',
                        height: '80%'
                    });
                });
            };

            var handleDatatable = function () {
                var exportable = [0, 1, 2, 3, 4, 5, 6];

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
                            "url": '{{route('customers.list.data')}}',
                            "data": {}
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
                            {data: 'images'},
                            {data: 'name'},
                            {data: 'email'},
                            {data: 'phone_number'},
                            {data: 'location'},
                            {data: 'city'},
                            {data: 'zip'},
                            {data: 'country'},
                            {data: 'purchases'},
                            {data: 'balance'},
                            {data: 'invoices'},
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
                    Page.listenRowEdit();
                    Page.listenRowDelete();
                });

                $('.refresh-toggle').click(function(){
                    dataTable.ajax.reload();
                });
            };

            return {
                init: function(){
                    handlePopupSelector();
                    handleNewRecord();
                    handleDatatable();
                },

                getThumbnailFromPath: function(input){
                    if(input.match(/^([\\\/][a-z_\-0-9\.%]+)+\.(jpg|png|jpeg)$/i)) {
                        return $(location).attr('protocol') + '//' + $(location).attr('host') + input;
                    }else{
                        return false;
                    }
                },

                initRemoveListener: function(){
                    $('.item .close').on('click', function(e){
                        e.preventDefault();

                        var item = $(this).closest('div.item');
                        var container = $(this).closest('.file-thumbnail');
                        var list = container.find('.file-thumbnail-list');
                        var placeholder = list.find('.file-thumbnail-placeholder');

                        if(list.find('.item').length < 2 ){
                            placeholder.show();
                        }

                        item.remove();
                    })
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
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush