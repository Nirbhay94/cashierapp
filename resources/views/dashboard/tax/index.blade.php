@extends('layouts.master')
@section('page_title', __('Tax Reports'))
@section('content')
	<div class="block-header row">
		<div class="col-xs-12 col-sm-6">
			<h2>
				{{__('DASHBOARD')}}
				<small>{{__('Tax')}}</small>
			</h2>
		</div>
		<div class="col-xs-12 col-sm-6 align-right hidden-xs">
			{!! Form::open(['class' => 'form-inline', 'method' => 'GET']) !!}
			<div class="row">
				<div class="col-sm-5">
					{!! Form::text('from', request()->get('from'), ['placeholder' => __('From'), 'class' => 'form-control datetimepicker']) !!}
				</div>
				<div class="col-sm-5">
					{!! Form::text('to', request()->get('to'), ['placeholder' => __('To'), 'class' => 'form-control datetimepicker']) !!}
				</div>
				<div class="col-sm-2">
					{!! Form::submit(__('Go'), ['class' => 'btn bg-purple']) !!}
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<div class="box-title">
						<h2>
							{{__('TAX REPORTS')}}
							<small>{{__('List of tax reports on every POS or invoice sale.')}}</small>
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
								<th>{{__('Customer')}}</th>
								<th>{{__('Amount')}}</th>
								<th>{{__('Date')}}</th>
								<th>{{__('POS Txn. ID')}}</th>
								<th>{{__('Invoice Token')}}</th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th class="all">{{__('ID')}}</th>
								<th>{{__('Customer')}}</th>
								<th>{{__('Amount')}}</th>
								<th>{{__('Date')}}</th>
								<th>{{__('POS Txn. ID')}}</th>
								<th>{{__('Invoice Token')}}</th>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script>
        var Page = function(){
            var dataTable;

            var handleDatetimePicker = function(){
                $('.datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
            };

            var handleDatatable = function () {
                var exportable = [0, 1, 2, 3, 4, 5];

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
                            "url": '{{route('dashboard.tax.data')}}',
                            "data": {
                                from: '{{request()->get('from')}}',
	                            to: '{{request()->get('to')}}'
                            }
                        },
                        rowId: 'id',

                        dom: "<'row'<'col-md-4 col-sm-6 hidden-xs'l><'col-md-4 hidden-sm hidden-xs'B><'col-md-4 col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",

                        buttons: [
                            {extend: 'csv', exportOptions: {columns: exportable}},
                            {extend: 'print', exportOptions: {columns: exportable}},
                            {extend: 'excel', exportOptions: {columns: exportable}},
                        ],

                        columns: [
                            {data: 'id'},
                            {data: 'customer_id'},
                            {data: 'tax'},
                            {data: 'date'},
                            {data: 'pos_transaction_id'},
                            {data: 'customer_invoice_id'},
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
                    // Page.listenRowEdit();
                    // Page.listenRowDelete();
                });

                $('.refresh-toggle').click(function(){
                    dataTable.ajax.reload();
                });
            };

            return {
                init: function(){
                    handleDatetimePicker();
                    handleDatatable();
                },
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush()