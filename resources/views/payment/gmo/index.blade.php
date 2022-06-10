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

        <div class="col-md-8">
            <a href="{{ route('payment.gmo.order.create') }}" class="btn btn-primary">Create Order</a>

            @if (auth()->user()->gmo_member_id)
            <a href="{{ route('payment.gmo.member.show', ['user' => auth()->id()]) }}" class="btn btn-info">Info Member Payment</a>
            @else
            <a href="{{ route('payment.gmo.member.create') }}" class="btn btn-primary">Regist member</a>
            @endif
        </div>

        <br><br>
        <div class="col-md-12 mt-2">
            <table class="table">
                <tr>
                    <th style="width: 250px;">OrderID</th>
                    <th style="width: 200px;">AccessID</th>
                    <th style="width: 200px;">AccessPass</th>
                    <th style="width: 200px;">Amount</th>
                    <th style="width: 250px;">Status</th>
                    <th style="width: 250px;">TimeCreate</th>
                    <th></th>
                </tr>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->access_id }}</td>
                    <td>{{ $order->access_pass }}</td>
                    <td>{{ $order->total_charge }}</td>
                    <td>{{ config('gmo-payment.order_stt.'.$order->status) }}</td>
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