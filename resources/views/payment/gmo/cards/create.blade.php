@extends('layouts.app', ['title' => 'GMO Payment - Card'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')
        
        <div class="col-md-8">
            <form action="{{ route('payment.gmo.card.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Input Card No') }}</label>
                    <div class="col-md-6">
                        <input type="text" pattern="\d*" class="form-control" name="card_number" value="{{ old('card_number', '') }}" maxlength="16" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Input Expire') }}</label>
                    <div class="col-md-6">
                        <input type="text" pattern="\d*" class="form-control" name="card_expire" value="{{ old('card_expire', '') }}" maxlength="4" required>
                    </div>
                </div>
                <a href="{{ route('payment.gmo.member.show', ['user' => auth()->id()]) }}" class="btn btn-warning">Back</a>
                <button type="submit" class="btn btn-secondary">Regist Card</button>
            </form>
        </div>
    </div>
</div>
@endsection