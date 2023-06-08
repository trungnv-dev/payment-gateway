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
    </div>
</div>
@endsection