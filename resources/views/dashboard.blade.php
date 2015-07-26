@extends('layout')

@section('content')
    <div class="jumbotron">
        <h1>Hello, {{ Auth::user()->name }}!</h1>
        <p>
            Enter some text below and it will be sent automatically as a reply when a task gets assigned to you.
        </p>
        <form action="{{ url() }}" method="post">
            <div class="form-group">
                <div class="input-group input-group-lg">
                    <input type="text" name="message" value="{{ Auth::user()->message }}" class="form-control" placeholder="Get creative!">
                    <span class="input-group-btn">
                        <button class="btn btn-primary">Save</button>
                    </span>
                </div>
            </div>
        </form>

        @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif
    </div>
@endsection