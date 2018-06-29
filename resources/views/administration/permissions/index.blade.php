@extends('layouts.master')
@section('page_title', __('Roles & Permissions'))
@section('content')
	<div class="block-header">
		<h2>{{__('ADMINISTRATION')}}
			<a href="{{route('administration.permissions.create')}}" class="pull-right btn btn-primary">
				{{__('Add')}}
			</a>
		</h2>
		<div class="clearfix"></div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<div class="box-title">
						<h2>{{__('ROLES & PERMISSION')}}</h2>
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
								<th>{{__('Name')}}</th>
								<th>{{__('Permission Level')}}</th>
								<th>{{__('Total Users')}}</th>
								<th class="all">{{__('Actions')}}</th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th class="all">{{__('ID')}}</th>
								<th>{{__('Name')}}</th>
								<th>{{__('Permission Level')}}</th>
								<th>{{__('Total Users')}}</th>
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
	<!-- Datatables -->
	<script>
        var Page = function(){
            var dataTable;

            var handleDatatable = function () {
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
                            "url": '{{route('administration.permissions.data')}}',
                            "data": {}
                        },
                        columns: [
                            {data: 'id'},
                            {data: 'name'},
                            {data: 'level'},
                            {data: 'total'},
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
                    Page.listenRowDelete();
                });
                
                $('.refresh-toggle').click(function(){
                    dataTable.ajax.reload();
                });
            };

            return {
                init: function(){
                    handleDatatable();
                },

                listenRowDelete: function () {
                    var data_id, data_link;

                    $('.delete-row').click(function(e){
                        if(data_id = $(this).data('id')){
                            data_link = $(this).attr('href');
                            swal({
                                title: "{{__('Are you sure?')}}",
                                text: "{{__('All affected users will be restored to the default user role!')}}",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "{{__('Yes, remove it!')}}",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true
                            }, function () {
                                $.ajax({
                                    url: data_link,
                                    method: 'DELETE',
                                    success: function() {
                                        swal("{{__('Successful')}}", "{{__('Role was successfully removed!')}}", "success");
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