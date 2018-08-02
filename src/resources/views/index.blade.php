@extends ('layouts.app')    

@section ('content')

    <div id="filevuer-main">
        <app :connections='{{ $connections }}' :logged-in='{{ $loggedIn }}' selected='{{ $selected }}'></app>
    </div>

@endsection

@push('css')
<link href="{{ asset('vendor/filevuer/css/filevuer.css') }}" rel="stylesheet">
@endpush

@push('js')
<script>
    window.Filevuer = {
        csrfToken: '{{ csrf_token() }}'
    }
</script>
<script src="{{ asset('vendor/filevuer/js/filevuer.js') }}"></script>
@endpush
