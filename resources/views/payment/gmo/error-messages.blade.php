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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible notice msg_server">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
