@extends('layouts.admin.app')

@section('content')
{{-- test --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">Quản lý danh mục</h1>
            <router-view name="categoryIndex"></router-view>
            <router-view></router-view>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection
