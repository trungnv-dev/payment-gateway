@extends('layouts.app', ['title' => 'GMO Payment - Create Order'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')
        
        <div class="col-md-8">
            <form action="{{ route('payment.gmo.order.store') }}" method="POST">
                @csrf

                <strong>SELECT PRODUCTS</strong><br><br>
                <table class="table">
                    <tr>
                        <th style="width: 25px;"></th>
                        <th style="width: 350px;">Name</th>
                        <th style="width: 100px;">Price</th>
                        <th style="width: 150px;">Image</th>
                    </tr>
                    @foreach ($products as $key => $product)
                    <tr>
                        <td><input class="form-check-input" type="checkbox" value="{{ $product->id }}" name="product[]" id="product{{ $key }}"></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            <img style="width: 80px; height: 80px;" src="{{ img_path($product->src) }}">
                        </td>
                    </tr>
                    @endforeach
                </table>
                <br>
                <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>
                <button type="submit" class="btn btn-secondary">Create Order</button>
            </form>
        </div>
    </div>
</div>
@endsection