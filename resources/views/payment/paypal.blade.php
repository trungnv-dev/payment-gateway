@extends('layouts.app', ['title' => 'PayPal'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Set up a input price -->
            <div class="row mb-3">
                <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Input price') }}</label>
                <div class="col-md-6">
                    <input id="price" type="number" class="form-control" name="price" value="{{ old('price', 2) }}" required>
                </div>
            </div>
            <!-- Set up a container element for the button -->
            <div id="paypal-button-container"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Replace "test" with your own sandbox Business account app client ID -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.sandbox.client_id') }}"></script>
<!-- Handle -->
<script>
    paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: document.getElementById('price').value // Can also reference a variable or function
                    }
                }]
            });
        },
        // Finalize the transaction after payer approval
        onApprove: (data, actions) => {
            return actions.order.capture().then(function(orderData) {
                // Successful capture! For dev/demo purposes:
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                const transaction = orderData.purchase_units[0].payments.captures[0];
                alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                // When ready to go live, remove the alert and show a success message within this page. For example:
                // const element = document.getElementById('paypal-button-container');
                // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                // Or go to another URL:  actions.redirect('thank_you.html');
            });
        }
    }).render('#paypal-button-container');
</script>
@endpush
