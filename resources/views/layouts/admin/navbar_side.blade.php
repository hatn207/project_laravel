<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            {{-- <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li> --}}
            {{-- <li>
                <a href="/"><i class="fa fa-dashboard fa-fw"></i> Bảng điều khiển</a>
            </li> --}}
            <li>
                <a class="{{ Request::segment(2) === 'analytic' ? 'active' : Request::segment(2) }}" href="{{ route('admin.analytic.index', ['all' => 'index']) }}"><i class="fa fa-dashboard fa-fw"></i> Bảng điều khiển</a>
            </li>
            <li>
                <a class="{{ Request::segment(2) === 'category' ? 'active' : Request::segment(2) }}" href="{{ route('admin.category.index', ['all' => 'list']) }}"><i class="fa fa-tags"></i> Quản lý danh mục</a>
            </li>
            <li>
                <a class="{{ Request::segment(2) === 'article' ? 'active' : Request::segment(2) }}" href="{{ route('admin.article.index', ['all' => 'list']) }}"><i class="fa fa-newspaper-o"></i> Quản lý bài viết</a>
            </li>
            <li>
                <a class="{{ Request::segment(2) === 'rss' ? 'active' : Request::segment(2) }}" href="{{ route('admin.rss.index', ['all' => 'list']) }}"><i class="fa fa-rss"></i> Quản lý Rss</a>
            </li>
            <li>
                <a class="{{ Request::segment(2) === 'rss-article' ? 'active' : Request::segment(2) }}" href="{{ route('admin.rss_article.index', ['all' => 'list']) }}"><i class="fa fa-newspaper-o"></i> Quản lý bài viết Rss</a>
            </li>
            <li>
                <a class="{{ Request::segment(2) === 'trend' ? 'active' : Request::segment(2) }}" href="{{ route('admin.trend.index', ['all' => 'list']) }}"><i class="fa fa-tags"></i> Quản lý xu hướng</a>
            </li>
            {{--  <li>
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="#">Second Level Item</a>
                    </li>
                    <li>
                        <a href="#">Second Level Item</a>
                    </li>
                    <li>
                        <a href="#">Third Level <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                        </ul>
                        <!-- /.nav-third-level -->
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>  --}}
            {{--  <li class="active">
                <a href="#"><i class="fa fa-files-o fa-fw"></i> Manage Rss<span class="fa arrow"></span></a>
                {{--  <ul class="nav nav-second-level">
                    <li>
                        <a class="active" href="blank.html">List</a>
                    </li>
                    <li>
                        <a href="login.html">Login Page</a>
                    </li>
                </ul>  --}}
                <!-- /.nav-second-level -->
            {{--  </li>  --}}
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>  