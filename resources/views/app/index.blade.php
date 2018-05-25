@extends('layouts.app.app')

@section('content')
<div class="row">
    <router-view name="home"></router-view>
    <router-view></router-view>
    <!-- col-md-8 -->

    <widgetright></widgetright>
    <!-- col-md-4 -->
</div>

@endsection
