@extends('layouts.app', ['title' => 'File Stream'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-2 text-center">
            <a href="{{ route('file_stream.export') }}" class="btn col-md-6 btn-warning">Export data large into file doesn't exists.</a>
        </div>
        <div class="col-md-12 mt-2 text-center">
            <a href="{{ route('file_stream.download') }}" class="btn col-md-6 btn-success">Download file data large exists.</a>
        </div>
        <div class="col-md-12 mt-2 text-center">
            <a href="{{ route('file_stream.copy') }}" class="btn col-md-6 btn-danger">Copy file data large from s3 save to local.</a>
        </div>
        <div class="col-md-8 mt-3">
            <div class="card">
                <div class="card-header">{{ __('Import CSV') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('file_stream.import_csv') }}" enctype="multipart/form-data">
                        @csrf

                        @if(session()->has('message'))
                            <div class="message-box">
                                <p class="text-success">{{ session('message') }} <a href="{{ route('batches.info', session('batchId')) }}">Follow here!</a></p>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-10">
                                <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" accept=".csv" required>

                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Import') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
