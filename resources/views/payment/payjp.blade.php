@extends('layouts.app', ['title' => 'PayJP'])

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
            <form action="{{ route('post.payjp') }}" method="post">
                @csrf

                <div class="row mb-3">
                    <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Input price') }}</label>
                    <div class="col-md-6">
                        <input type="number" class="form-control" name="price" value="{{ old('price', 100) }}" required>
                    </div>
                </div>

                <script type="text/javascript" src="https://checkout.pay.jp/" class="payjp-button" data-key="{{ config('payjp.public_key') }}" data-text="カード情報を入力" data-submit-text="カードを登録する"></script>

                @if (!empty($cardList))
                <br>
                <div class="card">
                    <div class="card-header"><strong>{{ __('もしくは登録済みのカードで支払い') }}</strong></div>

                    <div class="card-body">
                        @foreach ($cardList as $card)
                        <div class="card-item">
                            <label>
                                <input type="radio" name="payjp_card_id" value="{{ $card['id'] }}" />
                                <span class="brand">{{ $card['brand'] }}</span>
                                <span class="number">{{ $card['cardNumber'] }}</span>
                            </label>
                            <div>
                                <span>名義: {{ $card['name'] }}</span><br>
                                <span>期限: {{ $card['exp_year'] }}/{{ $card['exp_month'] }}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach

                        <button type="submit" class="btn btn-secondary">選択したカードで決済する</button>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection