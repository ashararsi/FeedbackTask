@extends('layouts.app')
@section('title')
    Feedback Edit
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <edit :feedback='{!! json_encode($feedback) !!}'></edit>
            </div>
        </div>
    </div>
@endsection
