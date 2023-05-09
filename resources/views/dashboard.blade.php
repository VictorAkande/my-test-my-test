@extends('layouts.layouts')

@section('title', 'Login')

@section('content')

    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="text">
        <h>Hello! {{$role}}</h>
    </div>
@endsection
