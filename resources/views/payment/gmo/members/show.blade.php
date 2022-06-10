@extends('layouts.app', ['title' => 'GMO Payment - Member'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')

        <div class="col-md-8">
            <div class="form-group row">
                <label for="memberId" class="col-sm-2 col-form-label">MemberID</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="memberId" value="{{ $member['MemberID'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="memberName" class="col-sm-2 col-form-label">MemberName</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="memberName" value="{{ $member['MemberName'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="status" value="{{ $member['DeleteFlag'] ? 'Inactive' : 'Active' }}">
                </div>
            </div>

            <form class="mt-2" action="{{ route('payment.gmo.member.destroy', ['user' => auth()->id()]) }}" method="POST">
                @csrf
                @method('DELETE')
                <a href="{{ route('payment.gmo.member.edit', ['user' => auth()->id()]) }}" class="btn btn-primary">Edit Member</a>
                <button type="submit" class="btn btn-danger">Delete Member</button>
                @if (!isset($cards['CardSeq']) || (isset($cards['CardSeq']) && count(explode('|', $cards['CardSeq'])) < 5))
                <a href="{{ route('payment.gmo.card.create') }}" class="btn btn-primary">Regist Card Member</a>
                @endif
            </form>
            <br>
        </div>

        @if (isset($cards['CardSeq']))
        <div class="col-md-8 mt-2">
            <table class="table">
                <tr>
                    <th style="width: 250px;">CardNo</th>
                    <th style="width: 200px;">Expire</th>
                    <th style="width: 200px;">Brand</th>
                    <th style="width: 200px;">Status</th>
                    <th style="width: 250px;">Delete</th>
                </tr>
                @for ($i = 0; $i < count(explode('|', $cards['CardSeq'])); $i++)
                <tr>
                    <td>{{ explode('|', $cards['CardNo'])[$i] }}</td>
                    <td>{{ explode('|', $cards['Expire'])[$i] }}</td>
                    <td>{{ explode('|', $cards['Brand'])[$i] }}</td>
                    <td>{{ explode('|', $cards['DeleteFlag'])[$i] ? 'Inactive' : 'Active' }}</td>
                    <td>
                        <form action="{{ route('payment.gmo.card.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="card_seq" value="{{ explode('|', $cards['CardSeq'])[$i] }}">
                            <button type="submit" class="btn btn-danger">Delete Card</button>
                        </form>
                    </td>
                </tr>
                @endfor
            </table>
        </div>
        @endif
    </div>
</div>
@endsection