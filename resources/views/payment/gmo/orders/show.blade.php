@extends('layouts.app', ['title' => 'GMO Payment - Order'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')

        <div class="col-md-8">
            <div class="form-group row">
                <label for="orderId" class="col-sm-2 col-form-label">OrderID</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="orderId" value="{{ $transaction['OrderID'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="accessId" class="col-sm-2 col-form-label">AccessID</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="accessId" value="{{ $transaction['AccessID'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">AccessPass</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="status" value="{{ $transaction['AccessPass'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="amount" value="{{ $transaction['Amount'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">JobCd</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="jobCd" value="{{ $transaction['JobCd'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="status" value="{{ $transaction['Status'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="time" class="col-sm-2 col-form-label">TimeCreate</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="time" value="{{ $order->created_at }}">
                </div>
            </div>
            <br>
        </div>

        <div class="col-md-8 mt-2">
            @if (in_array($transaction['Status'], App\Enums\GMOPayment::STATUS_UNPAID) && Gate::denies('admin'))
            <form class="mt-2" action="{{ route('payment.gmo.order.execTran', ['order' => $order->id]) }}" method="POST">
                @csrf
                <strong>CHOOSE PAYMENT METHODS</strong>
                <div class="select-type-payment mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type_pg" id="type-input" value="1">
                        <label class="form-check-label" for="type-input">Input CardNumber</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type_pg" id="type-select" value="2">
                        <label class="form-check-label" for="type-select">Select CardNumber</label>
                    </div>
                </div>

                <div class="input-card-number d-none">
                    <div class="form-group mt-2 row">
                        <label for="cardNumber" class="col-sm-2 col-form-label">Input NumberCard</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="" placeholder="Input Card Number">
                        </div>
                    </div>
                    <div class="form-group mt-2 row">
                        <label for="expire" class="col-sm-2 col-form-label">Input Expire</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="expire" name="expire" value="" placeholder="Input Expire, Ex: YYMM => 2602">
                        </div>
                    </div>
                </div>

                <div class="select-card-number d-none">
                    @if (isset($cards['CardSeq']))
                    <div class="col-md-8 mt-2">
                        <table class="table">
                            <tr>
                                <th></th>
                                <th style="width: 250px;">CardNo</th>
                                <th style="width: 200px;">Expire</th>
                                {{-- <th style="width: 200px;">Brand</th> --}}
                            </tr>
                            @for ($i = 0; $i < count(explode('|', $cards['CardSeq'])); $i++)
                            <tr>
                                <td><input type="radio" name="card_seq" value="{{ explode('|', $cards['CardSeq'])[$i] }}"></td>
                                <td>{{ explode('|', $cards['CardNo'])[$i] }}</td>
                                <td>{{ explode('|', $cards['Expire'])[$i] }}</td>
                                {{-- <td>{{ explode('|', $cards['Brand'])[$i] }}</td> --}}
                            </tr>
                            @endfor
                        </table>
                    </div>
                    @else
                        @if (auth()->user()->gmo_member_id)
                        <p class="mt-2">Click <a href="{{ route('payment.gmo.card.create') }}">here</a> go to the card registration screen.</p>
                        @else
                        <p class="mt-2">Click <a href="{{ route('payment.gmo.member.create') }}">here</a> go to the member registration screen.</p>
                        @endif
                    @endif
                </div>

                <div class="mt-2">
                    <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>
                    <button type="submit" class="btn btn-danger d-none" id="btn-pay">Pay</button>
                </div>
            </form>

            @elseif (in_array($transaction['Status'], App\Enums\GMOPayment::STATUS_PAID))
            <form class="mt-2" action="{{ route('payment.gmo.order.alterTran', ['order' => $order->id]) }}" method="POST">
                @csrf
                <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>

                @if (Gate::denies('admin'))
                <input type="hidden" name="cancel" value="1">
                <button type="submit" class="btn btn-danger">Cancel</button>

                @elseif ($transaction['Status'] == App\Enums\GMOPayment::STATUS_PAID["AUTH"])
                <input type="hidden" name="cancel" value="0">
                <button type="submit" class="btn btn-success">Sales</button>

                @endif
            </form>

            @else
            <div class="mt-2">
                <a href="{{ route('payment.gmo.index') }}" class="btn btn-warning">Back</a>
            </div>
            @endif
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

@push ('scripts')
    <script>
        $(document).ready(function () {
            $("input[name='type_pg']").click(function () {
                if ($(this).val() == 1) {
                    $(".input-card-number").removeClass('d-none');
                    $(".select-card-number").addClass('d-none');
                } else {
                    $(".input-card-number").addClass('d-none');
                    $(".select-card-number").removeClass('d-none');
                }
                $("#btn-pay").removeClass('d-none');
            });
        });
    </script>
@endpush
