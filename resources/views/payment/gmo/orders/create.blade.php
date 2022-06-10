@extends('layouts.app', ['title' => 'GMO Payment - Create Order'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')
        
        <div class="col-md-8">
            <form action="{{ route('payment.gmo.order.store') }}" method="POST">
                @csrf

                <strong>SELECT PRODUCTS</strong><br><br>
                @foreach ($products as $key => $product)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $product->id }}" name="product[]" id="product{{ $key }}">
                    <label class="form-check-label" for="product{{ $key }}">
                        {{ $product->name }}
                    </label>
                </div>
                @endforeach
                <br>
                <button type="submit" class="btn btn-secondary">Create Order</button>
            </form>
        </div>
    </div>
</div>
@endsection