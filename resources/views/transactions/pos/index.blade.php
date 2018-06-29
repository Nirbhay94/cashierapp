@extends('layouts.master')
@section('page_title', __('POS Transactions'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('TRANSACTIONS')}}
			<small class="clearfix">
				{{__('Pos')}}
			</small>
		</h2>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<div class="box-title">
						<h2>
							{{__('POS TRANSACTIONS')}}
							<small>{{__('List of pos transactions')}}</small>
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
								<th>{{__('Details')}}</th>
								<th class="none">{{__('Sub Total')}}</th>
								<th class="none">{{__('Tax')}}</th>
								<th class="none">{{__('Total')}}</th>
								<th class="none">{{__('Invoice')}}</th>
								<th>{{__('Note')}}</th>
								<th>{{__('Status')}}</th>
								<th>{{__('Date')}}</th>
								<th class="none">{{__('Items')}}</th>
								<th class="all">{{__('Actions')}}</th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th class="all">{{__('ID')}}</th>
								<th>{{__('Customer')}}</th>
								<th>{{__('Details')}}</th>
								<th class="none">{{__('Sub Total')}}</th>
								<th class="none">{{__('Tax')}}</th>
								<th class="none">{{__('Total')}}</th>
								<th class="none">{{__('Invoice')}}</th>
								<th>{{__('Note')}}</th>
								<th>{{__('Status')}}</th>
								<th>{{__('Date')}}</th>
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
                var exportable = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

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
                            "url": '{{route('transactions.pos.data')}}',
                            "data": {}
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
                            {data: 'details'},
                            {data: 'sub_total'},
                            {data: 'tax'},
                            {data: 'total'},
                            {data: 'customer_invoice_id'},
                            {data: 'note'},
                            {data: 'status'},
                            {data: 'created_at'},
                            {data: 'items', orderable: false},
                            {data: 'action', orderable: false},
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
@endpush