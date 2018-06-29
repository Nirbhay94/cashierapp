@extends('layouts.master')
@section('page_title', __('Manage Users'))
@section('content')
    <div class="block-header">
        <h2>{{__('ADMINISTRATION')}}</h2>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>{{__('Showing Users')}}</strong>
                        <small>{{__('This is a table list of registered users')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li>
                            <a href="javascript:void(0);" class="refresh-toggle">
                                <i class="material-icons">loop</i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{route('administration.users.create')}}">
                                        <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                        {{__('Create User')}}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('administration.deleted-users.index')}}">
                                        <i class="fa fa-fw fa-group" aria-hidden="true"></i>
                                        {{__('Deleted Users')}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive users-table">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="table_data">
                            <thead>
                            <tr>
                                <th class="all">{{__('ID')}}</th>
                                <th>{{__('Username')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('First Name')}}</th>
                                <th>{{__('Last Name')}}</th>
                                <th>{{__('Balance')}}</th>
                                <th>{{__('Roles')}}</th>
                                <th class="none">{{__('Created')}}</th>
                                <th class="none">{{__('Updated')}}</th>
                                <th class="all">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="all">{{__('ID')}}</th>
                                <th>{{__('Username')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('First Name')}}</th>
                                <th>{{__('Last Name')}}</th>
                                <th>{{__('Balance')}}</th>
                                <th>{{__('Roles')}}</th>
                                <th class="none">{{__('Created')}}</th>
                                <th class="none">{{__('Updated')}}</th>
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
                            "url": '{{route('administration.users.data')}}',
                            "data": {}
                        },
                        
                        columns: [
                            {data: 'id'},
                            {data: 'name'},
                            {data: 'email'},
                            {data: 'first_name'},
                            {data: 'last_name'},
                            {data: 'balance'},
                            {data: 'roles'},
                            {data: 'created_at'},
                            {data: 'updated_at'},
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
                
                // Listen for refresh trigger...
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
                                text: "{{__('The user will loss his/her privilege on this platform!')}}",
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
                                        swal("{{__('Successful')}}", "{{__('User was successfully removed!')}}", "success");
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
