@extends('layouts.app', ['title' => 'GMO Payment - Member'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('payment.gmo.error-messages')
        
        <div class="col-md-8">
            <form action="{{ route('payment.gmo.member.update', ['user' => auth()->id()]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Input Name') }}</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="member_name" value="{{ old('member_name', $member['MemberName'] ?? '') }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-secondary">Edit</button>
            </form>
        </div>
    </div>
</div>
@endsection