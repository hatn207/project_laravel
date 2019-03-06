@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    {{-- tst2 --}}
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">Quản lý bài viết Rss</h1>
            <router-view name="rssArticleIndex"></router-view>
            <router-view></router-view>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection
