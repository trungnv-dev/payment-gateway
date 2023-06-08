<!DOCTYPE html>
<html lang="en">
<head>
  <title>Generate TOKEN GMO</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <div class="padding">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Credit Card</strong>
                        <small>enter your card details</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input class="form-control" id="cardname" type="text" placeholder="Enter your name" value="NGUYEN VAN A">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="ccnumber">Credit Card Number</label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" value="4111111111111111" id="cardno" placeholder="4111111111111111">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-credit-card"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="ccmonth">Month</label>
                                <select class="form-control" id="ccmonth">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05" selected>05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="ccyear">Year</label>
                                <select class="form-control" id="ccyear">
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023" selected>2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="cvv">CVV/CVC</label>
                                    <input class="form-control" id="cvv" type="text" value="123" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-success float-right" type="button" id="getToken">
                            <i class="mdi mdi-gamepad-circle"></i> Get token</button>
                    </div>
                    <br>
                    Token: <span id="token"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Confirm charge</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="https://testacsds.gmopg.jp/ThreeDSim/ACS" target="_blank">
                            <input type="text" class="form-control" name="PaReq" value="" placeholder="PaReq">
                            <input type="text" class="form-control" name="MD" value="" placeholder="MD">
                            <input type="text" class="form-control" name="TermUrl" value="http://localhost:4000/callback" placeholder="TermUrl">
                            <button class="btn btn-sm btn-success float-right" type="submit">
                                <i class="mdi mdi-gamepad-circle"></i> 3DS1</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://stg.static.mul-pay.jp/ext/js/token.js"></script>
<script>
    $(document).ready(function(){
        $("#getToken").click(function(){
            Multipayment.init("tshop00059503");
            Multipayment.getToken({
                holdername: $("#cardname").val(),
                cardno: $("#cardno").val(),
                expire: $('#ccyear').val() + $('#ccmonth').val(),
                securitycode: $("#cvv").val(),
            }, function (response) {
                if (response.resultCode != 000){
                    var tokenError = {
                        "000": "トークン取得正常終了",
                        "100": "カード番号必須チェックエラー",
                        "101": "カード番号フォーマットエラー(数字以外を含む)",
                        "102": "カード番号フォーマットエラー(10-16 桁の範囲外)",
                        "110": "有効期限必須チェックエラー",
                        "111": "有効期限フォーマットエラー(数字以外を含む)",
                        "112": "有効期限フォーマットエラー(6 又は 4 桁以外)",
                        "113": "有効期限フォーマットエラー(月が 13 以上)",
                        "121": "セキュリティコードフォーマットエラー(数字以外を含む)",
                        "122": "セキュリティコード桁数エラー",
                        "131": "名義人フォーマットエラー(半角英数字、一部の記号以外を含む)",
                        "132": "名義人フォーマットエラー(51 桁以上)",
                        "141": "発行数フォーマットエラー(数字以外を含む)",
                        "142": "発行数フォーマットエラー(1-10 の範囲外)",
                        "150": "カード情報を暗号化した情報必須チェックエラー",
                        "160": "ショップ ID 必須チェックエラー",
                        "161": "ショップ ID フォーマットエラー(14 桁以上)",
                        "162": "ショップ ID フォーマットエラー(半角英数字以外)",
                        "170": "公開鍵ハッシュ値必須チェックエラー",
                        "180": "ショップ ID または公開鍵ハッシュ値がマスターに存在しない",
                        "190": "カード情報(Encrypted)が復号できない",
                        "191": "カード情報(Encrypted)復号化後フォーマットエラー",
                        "501": "トークン用パラメータ(id)が送信されていない",
                        "502": "トークン用パラメータ(id)がマスターに存在しない",
                        "511": "トークン用パラメータ(cardInfo)が送信されていない",
                        "512": "トークン用パラメータ(cardInfo)が復号できない",
                        "521": "トークン用パラメータ(key)が送信されていない",
                        "522": "トークン用パラメータ(key)が復号できない",
                        "531": "トークン用パラメータ(callBack)が送信されていない",
                        "541": "トークン用パラメータ(hash)が存在しない",
                        "551": "トークン用 apikey が存在しない ID",
                        "552": "トークン用 apikey が有効ではない",
                        "901": "マルチペイメント内部のシステムエラー",
                        "902": "処理が混み合っている"
                    };
                    alert(tokenError[response.resultCode])
                } else {
                    cardToken = response.tokenObject.token;
                    $("#token").html(cardToken);
                }
            });
        })
    });
</script>
</body>
</html>