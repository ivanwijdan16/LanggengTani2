@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Dokumentasi Program</h2>

        <iframe src="{{ asset('dokumentasi.pdf') }}" frameborder="0" class="w-100" style="height: 70vh"></iframe>
    </div>
@endsection
