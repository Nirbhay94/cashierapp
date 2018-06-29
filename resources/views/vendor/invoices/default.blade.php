<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{ $invoice->name }}</title>
        <link href="{{asset('plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
        <style>
            h1,h2,h3,h4,p,span,div { font-family: DejaVu Sans; }
        </style>
    </head>
    <body>
        <div style="clear:both; position:relative;">
            <div style="position:absolute; left:0pt; width:250pt;">
                <img class="img-rounded" height="{{ $invoice->logo_height }}" src="{{ $invoice->logo }}">
            </div>
            <div style="margin-left:300pt;">
                <b>{{__('Date')}}: </b> {{ $invoice->date->formatLocalized('%A %d %B %Y') }}<br />
                @if ($invoice->number)
                    <b>{{__('Invoice')}} #: </b> {{ $invoice->number }} @if ($invoice->status) ({{strtoupper($invoice->status)}}) @endif<br />
                @endif
                @if ($invoice->amount_paid)
                    <b>{{__('Amount Paid')}}</b>:  {{$invoice->formatCurrency()->symbol . $invoice->amount_paid }} <br />
                @endif
            </div>
        </div>
        <br />
        <h2>{{ $invoice->name }} {{ $invoice->number ? '#' . $invoice->number : '' }}</h2>
        <div style="clear:both; position:relative;">
            <div style="position:absolute; left:0pt; width:250pt;">
                <h4>{{__('Business Details')}}:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        {!! $invoice->business_details->count() == 0 ? '<i>No business details</i><br />' : '' !!}
                        {{ $invoice->business_details->get('name') }}<br />
                        ID: {{ $invoice->business_details->get('id') }}<br />
                        {{ $invoice->business_details->get('phone') }}<br />
                        {{ $invoice->business_details->get('location') }}<br />
                        {{ $invoice->business_details->get('zip') }} {{ $invoice->business_details->get('city') }}
                        {{ $invoice->business_details->get('country') }}<br />
                    </div>
                </div>
            </div>
            <div style="margin-left: 300pt;">
                <h4>{{__('Customer Details')}}:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        {!! $invoice->customer_details->count() == 0 ? '<i>No customer details</i><br />' : '' !!}
                        {{ $invoice->customer_details->get('name') }}<br />
                        ID: {{ $invoice->customer_details->get('id') }}<br />
                        {{ $invoice->customer_details->get('phone') }}<br />
                        {{ $invoice->customer_details->get('location') }}<br />
                        {{ $invoice->customer_details->get('zip') }} {{ $invoice->customer_details->get('city') }}
                        {{ $invoice->customer_details->get('country') }}<br />
                    </div>
                </div>
            </div>
        </div>
        <h4>Items:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Item Name')}}</th>
                    <th>{{__('Price')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Total')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->get('id') }}</td>
                        <td>{{ $item->get('name') }}</td>
                        <td>{{ $item->get('price') }}</td>
                        <td>{{ $item->get('ammount') }}</td>
                        <td>{{ $item->get('totalPrice') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="clear:both; position:relative;">
            <div style="position:absolute; left:0pt; width:250pt;">
                @if($invoice->notes)
                    <h4>{{__('Notes')}}:</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ $invoice->notes }}
                        </div>
                    </div>
                @endif

                @if($invoice->showQrCode())
                    <h4>{{__('Scan To Pay')}}:</h4>
                    <img src="{{$invoice->getQrCode()}}" />
                @endif
            </div>
            
            <div style="margin-left: 300pt;">
                <h4>{{__('Total')}}:</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><b>{{__('Subtotal')}}</b></td>
                            <td>{{ $invoice->subTotalPriceFormatted() }} {{ $invoice->formatCurrency()->symbol }}</td>
                        </tr>
                        <tr>
                            <td>
                                <b>
                                    {{__('Taxes')}} {{ $invoice->tax_type == 'percentage' ? '(' . $invoice->tax . '%)' : '' }}
                                </b>
                            </td>
                            <td>{{ $invoice->taxPriceFormatted() }} {{ $invoice->formatCurrency()->symbol }}</td>
                        </tr>
                        <tr>
                            <td><b>{{__('TOTAL')}}</b></td>
                            <td><b>{{ $invoice->totalPriceFormatted() }} {{ $invoice->formatCurrency()->symbol }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($invoice->footnote)
            <br /><br />
            <div class="well" style="{{($invoice->showQrCode()) ? 'margin-left: 300pt;' : ''}}">
                {{ $invoice->footnote }}
            </div>
        @endif
    </body>
</html>
