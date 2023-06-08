@extends('layouts.app', ['title' => 'GMO Payment'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(session()->has('message'))
        <div class="alert alert-info alert-dismissible notice msg_server">
            <div>{{ session('message') }}</div>
        </div>
        @endif
        
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible notice msg_server">
            <div>{{ session('error') }}</div>
        </div>
        @endif

        <br><br>
        <div class="col-10 justify-content-center">
            <form method="GET">
                Order Date:
                <input type="date" class="w-25 d-inline form-control" name="date_to" value="{{ $dateTo }}"> -
                <input type="date" class="w-25 d-inline form-control" name="date_from" value="{{ $dateFrom }}">
                <input type="submit" class="btn btn-primary" value="Search" />
                @if (Gate::denies('admin'))
                    <div class="col-md-8 d-inline">
                        <a href="{{ route('payment.gmo.order.create') }}" class="btn btn-primary">Create Order</a>

                        @if (auth()->user()->gmo_member_id)
                        <a href="{{ route('payment.gmo.member.show', ['user' => auth()->id()]) }}" class="btn btn-info">Info Member Payment</a>
                        @else
                        <a href="{{ route('payment.gmo.member.create') }}" class="btn btn-primary">Regist member</a>
                        @endif
                    </div>
                @endif
            </form>
        </div>
        <div class="col-md-12 mt-2">
            <table class="table">
                <tr>
                    <th style="width: 300px;">OrderID</th>
                    <th style="width: 200px;">AccessID</th>
                    <th style="width: 200px;">AccessPass</th>
                    <th style="width: 100px;">Amount</th>
                    <th style="width: 150px;">Secure Regist</th>
                    <th style="width: 120px;">Status Paid</th>
                    <th style="width: 250px;">TimeCreate</th>
                    <th></th>
                </tr>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_id }}</td>
                    <td>{{ $order->access_id }}</td>
                    <td>{{ $order->access_pass }}</td>
                    <td>{{ $order->total_charge }}</td>
                    <td>{{ App\Enums\GMOPayment::STT_SECURE[$order->secure] }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <a href="{{ route('payment.gmo.order.show', ['order' => $order->id]) }}">Detail</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection