@extends('layouts.app', ['title' => 'GMO Payment'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-2 text-center">
            <a href="#" class="btn col-md-6 btn-warning">LINE PAY</a>
        </div>
        <div class="col-md-12 mt-2 text-center">
            <a href="{{ route('payment.gmo.paypay') }}" class="btn col-md-6 btn-success">PAY PAY</a>
        </div>
        <div class="col-md-12 mt-2 text-center">
            <a href="{{ route('payment.gmo.credit_card') }}" class="btn col-md-6 btn-danger">CREDIT CARD</a>
        </div>
    </div>
</div>
@endsection