@extends('layout')

@section('content')
    <div class="page-header">
        <h1>ML Task Responder</h1>
    </div>
    <a href="{{ url('authenticate') }}" class="btn btn-primary btn-lg" role="button">
        Login with Asana
    </a>
@endsection