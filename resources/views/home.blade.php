@extends('layouts.app')

@section('title')
    Home
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <feedback_home :data='{!! json_encode($feedback) !!}' :users='{!! json_encode($users) !!}'  ></feedback_home>
            </div>
        </div>
    </div>
    </div>
@endsection
