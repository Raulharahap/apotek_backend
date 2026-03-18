@extends('landing-page.layouts.app')

@section('title', 'Home - APOTEK')

@section('content')
@include('landing-page.partials._navbar')
@include('landing-page.partials._hero')
@include('landing-page.partials._about')
@include('landing-page.partials._newsletter')
@include('landing-page.partials._service')
@include('landing-page.partials._feature')
@include('landing-page.partials._client')
@include('landing-page.partials._testimonial')

@endsection