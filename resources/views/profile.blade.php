@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <profile_edit :user='{!! json_encode(auth()->user()) !!}'></profile_edit>


            </div>
        </div>
    </div>
@endsection
