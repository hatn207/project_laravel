@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">Thống kê Google Analytics</h1>
            <div class="row">
                <div class="col-lg-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Thống kê số lần xem, người dùng.
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Lọc theo
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="{{ route('admin.analytic.index', ['index', 'search' => '10days']) }}">Mặc định</a>
                                        </li>
                                        <li><a href="{{ route('admin.analytic.index', ['index', 'search' => '7days']) }}">7 ngày</a>
                                        </li>
                                        <li><a href="{{ route('admin.analytic.index', ['index', 'search' => '1month']) }}">1 tháng</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            {!! $chartjs->render() !!}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-group fa-3x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                        <div class="huge">{{ $visitors }}</div>
                                            <div>Người dùng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-area-chart fa-3x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                        <div class="huge">{{ $page_views }}</div>
                                            <div>Số lần xem trang</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Thống kê chi tiết
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Trang</th>
                                    <th>Số lần xem trang</th>
                                    <th>Số lần xem trang duy nhất</th>
                                    <th>Thời gian trung bình trên trang</th>
                                    <th>Lượt truy cập</th>
                                    <th>Thoát ra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cls_data as $key => $value)
                                <tr>
                                    <td><a href="{{ $value['pagePath'] }}">{{ $value['pagePath'] }}</a></td>
                                    <td>{{ $value['pageViews'] }}</td>
                                    <td>{{ $value['pageViewsUnique'] }}</td>
                                    <td>{{ $value['timeOnPage'] }}</td>
                                    <td>{{ $value['sessions'] }}</td>
                                    <td>{{ $value['exits'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-6 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection
