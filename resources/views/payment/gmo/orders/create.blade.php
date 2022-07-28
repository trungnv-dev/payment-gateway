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
                {{ $products->links() }}<br>
                <strong>SELECT JOBCD</strong><br><br>
                <div class="form-check">
                    <input class="form-check-input" name="job_cd" type="radio" value="0" id="job_cd_0" checked>
                    <label class="form-check-label" for="job_cd_0">
                        Capture
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="job_cd" type="radio" value="1" id="job_cd_1">
                    <label class="form-check-label" for="job_cd_1">
                        Auth
                    </label>
                </div>
                <br>
                <strong>SELECT 3DS</strong><br><br>
                <div class="form-check">
                    <input class="form-check-input" name="td_flag" type="radio" value="0" id="td_flag_0" checked>
                    <label class="form-check-label" for="td_flag_0">
                        No 3DS
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="td_flag" type="radio" value="1" id="td_flag_1">
                    <label class="form-check-label" for="td_flag_1">
                        3DS-1
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="td_flag" type="radio" value="2" id="td_flag_2">
                    <label class="form-check-label" for="td_flag_2">
                        3DS-2
                    </label>
                </div>
                <br>
                <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>
                <button type="submit" class="btn btn-secondary">Create Order</button>
            </form>
        </div>
    </div>
</div>
@endsection