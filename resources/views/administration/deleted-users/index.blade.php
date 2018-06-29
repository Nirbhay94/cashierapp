@extends('layouts.master')
@section('page_title', __('Deleted Users List'))
@section('content')
    <div class="block-header">
        <h2>{{__('ADMINISTRATION')}}</h2>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="header bg-red">
                    <h2>
                        <strong>{{__('Showing Deleted Users')}}</strong>
                        <small>{{__('This is a table list of users who deleted their profiles.')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li>
                            <a href="{{route('administration.users.index')}}">
                                <i class="material-icons">reply</i>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="refresh-toggle">
                                <i class="material-icons">loop</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="table-responsive users-table">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="table_data">
                            <thead>
                            <tr>
                                <th class="all">{{__('ID')}}</th>
                                <th>{{__('Username')}}</th>
                                <th>{{__('Email')}}</th>
                                <th class="none">{{__('First Name')}}</th>
                                <th class="none">{{__('Last Name')}}</th>
                                <th>{{__('Role')}}</th>
                                <th>{{__('Deleted')}}</th>
                                <th class="none">{{__('Deleted IP')}}</th>
                                <th class="all">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="all">{{__('ID')}}</th>
                                <th>{{__('Username')}}</th>
                                <th>{{__('Email')}}</th>
                                <th class="none">{{__('First Name')}}</th>
                                <th class="none">{{__('Last Name')}}</th>
                                <th>{{__('Role')}}</th>
                                <th>{{__('Deleted')}}</th>
                                <th class="none">{{__('Deleted IP')}}</th>
                                <th class="all">{{__('Actions')}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-delete')

@endsection

@push('js')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.tooltips')
    
    <script>
        var Page = function(){
            var dataTable;

            var handleDatatable = function () {
                dataTable =  $('#table_data')
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
                            "url": '{{route('administration.deleted-users.data')}}',
                            "data": {}
                        },
                        columns: [
                            {data: 'id'},
                            {data: 'name'},
                            {data: 'email'},
                            {data: 'first_name'},
                            {data: 'last_name'},
                            {data: 'roles'},
                            {data: 'deleted_at'},
                            {data: 'deleted_ip_address'},
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
                    Page.listenRowRefresh();
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
                        e.preventDefault();
                        
                        if(data_id = $(this).data('id')){
                            data_link = $(this).attr('href');
                            
                            swal({
                                title: "{{__('Are you sure?')}}",
                                text: "{{__('You are about to completely delete users record!')}}",
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
                                        swal("{{__('Successful')}}", "{{__('User record was completely removed!')}}", "success");
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

                listenRowRefresh: function (){
                    var data_id, data_link;

                    $('.refresh-row').click(function(e){
                        e.preventDefault();
                        $(this).find('span.material-icons').addClass('material-spin');
                        
                        if(data_id = $(this).data('id')){
                            data_link = $(this).attr('href');
                            
                            $.ajax({
                                url: data_link,
                                method: 'PUT',
                                success: function() {
                                    swal("{{__('Successful')}}", "{{__('User was successfully restored!')}}", "success");
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
                        }
                        
                        return false;
                    });
                },
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush