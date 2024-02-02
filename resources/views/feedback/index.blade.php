@extends('layouts.app')

@section('title')
    Feedback
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <index :data='{!! json_encode($data) !!}'></index>
        </div>
    </div>
@endsection
