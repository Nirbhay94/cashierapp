@extends('layouts.master')
@section('page_title', __('Invoices'))
@section('content')
    <div class="block-header">
        <h2>{{__('SUBSCRIPTION')}}</h2>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <div class="box-title">
                        {{__('Transaction Invoices')}}
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
                                <th>{{__('User')}}</th>
                                <th>{{__('Transaction ID')}}</th>
                                <th>{{__('Note')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th class="all">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="all">{{__('ID')}}</th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Transaction ID')}}</th>
                                <th>{{__('Note')}}</th>
                                <th>{{__('Amount')}}</th>
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
                        "url": '{{route('subscription.invoices.data')}}',
                        "data": {}
                    },
                    columns: [
                        {data: 'id'},
                        {data: 'user_id'},
                        {data: 'transaction_id'},
                        {data: 'note'},
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

                $('.refresh-toggle').click(function(){
                    dataTable.ajax.reload();
                });
            };
            
            return {
                init: function(){
                    handleDatatable();
                }
            }
        }();
        
        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush