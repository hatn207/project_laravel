<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createTrend'}" class="btn btn-success">Tạo mới</router-link>
            <a href="#" class="btn btn-info pull-right" v-on:click="getGTrends()">
                Lấy từ Google Trends
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Danh sách</div>
            <div class="panel-body">
                <pulse-loader :loading="loading"></pulse-loader>
                <div class="table-responsive" v-if="show">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Xu hướng</th>
                            <th>Thống kê</th>
                            <th>Ngày tạo</th>
                            <th width="100">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in rows">
                            <td>{{ index+1 }}</td>
                            <td>{{ row.content }}</td>
                            <td>
                                <router-link :to="{name: 'showTrend', params: {slug: row.slug+'.article'}}">
                                    Bài viết ({{ row.articles_count }})
                                </router-link>
                                |
                                <router-link :to="{name: 'showTrend', params: {slug: row.slug+'.rssarticle'}}">
                                    Bài viết RSS ({{ row.rssarticles_count }})
                                </router-link>
                            </td>
                            <td>{{ row.created_at }}</td>
                            <td>
                                <!-- <router-link :to="{name: 'editCategory', params: {id: row.id}}" class="btn btn-xs btn-primary">
                                    Sửa
                                </router-link> -->
                                <a href="#"
                                class="btn btn-xs btn-danger"
                                v-on:click="deleteEntry(row.id, index)">
                                    Xoá
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                
                <div v-if="pagination.last_page > 1" class="pagination">
                    <ul class="pagination">
                        <li v-if="pagination.current_page > 1">
                            <a href="javascript:void(0)" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)">
                                <span aria-hidden="true">«</span>
                            </a>
                        </li>
                        <li v-for="page in pagesNumber" :class="{'active': page == pagination.current_page}">
                            <a href="javascript:void(0)" v-on:click.prevent="changePage(page)">{{ page }}</a>
                        </li>
                        <li v-if="pagination.current_page < pagination.last_page">
                            <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)">
                                <span aria-hidden="true">»</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                
            </div>
            
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                rows: [],
                counter: 0,
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                show: false,
                loading: false
            }
        },
        computed: {
            pagesNumber() {
                var app = this;
                if (!app.pagination.to) {
                return [];
                }
                let from = app.pagination.current_page - app.offset;
                if (from < 1) {
                from = 1;
                }
                let to = from + (app.offset * 2);
                if (to >= app.pagination.last_page) {
                to = app.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = from; page <= to; page++) {
                pagesArray.push(page);
                }
                return pagesArray;
            }
        },
        mounted() {
            var app = this;
            app.getData(app.pagination.current_page)
        },
        methods: {
            changePage(page) {
                var app = this;
                app.pagination.current_page = page;
                app.getData(app.pagination.current_page);
            },
            getData(page) {
                var app = this;
                app.loading = true;
                axios.get('/api/v1/trend?page='+page)
                .then(function (resp) {
                    app.rows = resp.data.data;
                    app.pagination = resp.data;
                    // console.log(app.pagination);
                    if (app.rows.length != 0) {
                        app.show = true;
                    }
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load data");
                });
            },
            deleteEntry(id, index) {
                if (confirm("Bạn có muốn xoá nó?")) {
                    var app = this;
                    axios.delete('/api/v1/trend/' + id)
                        .then(function (resp) {
                            app.rows.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete");
                        });
                }
            },
            getGTrends() {
                var app = this;
                app.loading = true;
                axios.get('/api/v1/trend/create')
                .then(function (resp) {
                    app.getData(app.pagination.current_page);
                    app.loading = false;
                })
                .catch(function (resp) {
                    alert("Could not load data");
                });
            }
        }
    }
</script>