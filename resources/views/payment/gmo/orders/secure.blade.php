<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=Windows-31J">
</head>

<body OnLoad='OnLoadEvent();'>
    <form name="ACSCall" action="{{ $transaction['ACSUrl'] }}" method="POST">
        <noscript>
            <br>
            <br>
            <center>
                <h2>
                    3-Dセキュア認証を続けます。<br>
                    ボタンをクリックしてください。
                </h2>
                <input type="submit" value="OK">
            </center>
        </noscript>
        <input type="hidden" name="PaReq" value="{{ $transaction['PaReq'] }}">
        <input type="hidden" name="TermUrl" value="{{ route('payment.gmo.order.secureTran', ['order' => $transaction['orderId']]) }}">
        <input type="hidden" name="MD" value="{{ $transaction['MD'] }}">
    </form>
    <script>
        function OnLoadEvent() {
            document.ACSCall.submit();
        }
    </script>
</body>

</html>