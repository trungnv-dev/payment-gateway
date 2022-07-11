@extends('layouts.app', ['title' => 'GMO Payment - Order'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')

        <div class="col-md-8">
            <div class="form-group row">
                <label for="orderId" class="col-sm-2 col-form-label">OrderID</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="orderId" value="{{ $order->id }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="accessId" class="col-sm-2 col-form-label">AccessID</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="accessId" value="{{ $order->access_id }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">AccessPass</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="status" value="{{ $order->access_pass }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="amount" value="{{ $order->total_charge }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="status" value="{{ config('gmo-payment.order_stt.'.$order->status) }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="time" class="col-sm-2 col-form-label">TimeCreate</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="time" value="{{ $order->created_at }}">
                </div>
            </div>

            @unless ($order->status)
            <form class="mt-2" action="{{ route('payment.gmo.order.execTran', ['order' => $order->id]) }}" method="POST">
                @csrf
                <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>
                <button type="submit" class="btn btn-danger">Pay</button>
            </form>
            @else
            <form class="mt-2" action="{{ route('payment.gmo.order.alterTran', ['order' => $order->id]) }}" method="POST">
                @csrf
                <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>
                <button type="submit" class="btn btn-danger">Edit</button>
            </form>
            @endunless
            <br>
        </div>

        <div class="col-md-8 mt-2">
            <strong>LIST PRODUCTS CHOOSED</strong>
            <table class="table">
                <tr>
                    <th style="width: 250px;">Name</th>
                    <th style="width: 200px;">Price</th>
                    <th style="width: 200px;">Image</th>
                </tr>
                @foreach ($order->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <img style="width: 80px; height: 80px;" src="{{ img_path($product->src) }}">
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection