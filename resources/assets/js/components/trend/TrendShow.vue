<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'indexTrend'}" class="btn btn-default">Quay lại</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Danh sách</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên website</th>
                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>
                            <th>Ngày tạo</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(rssArticle, index) in rssArticles">
                            <td>{{ index+1 }}</td>
                            <td>{{ rssArticle.website_name }}</td>
                            <td><a :href="rssArticle.website_url" target="_blank">{{ rssArticle.title }}</a></td>
                            <td><img width="150" :src="rssArticle.thumb" /></td>
                            <td>{{rssArticle.pub_date}}</td>
                            <td width="90">
                                <router-link :to="{name: routeEdit, params: {id: rssArticle.id}}" class="btn btn-xs btn-primary">
                                    Sửa
                                </router-link>
                                <a v-if="deleteFlag" href="#"
                                class="btn btn-xs btn-danger"
                                v-on:click="deleteEntry(rssArticle.id, index)">
                                    Xóa
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
                rssArticles: [],
                counter: 0,
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                routeEdit: 'editTrendRssArticle',
                deleteFlag: false
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
            app.getRss(app.pagination.current_page)
            
        },
        methods: {
            changePage(page) {
                var app = this;
                app.pagination.current_page = page;
                app.getRss(app.pagination.current_page);
            },
            getRss(page) {
                var app = this;
                var slug = app.$route.params.slug;
                axios.get('/api/v1/trend/'+ slug +'?page='+page)
                .then(function (resp) {
                    app.rssArticles = resp.data.data;
                    app.pagination = resp.data;
                    var routePath = app.$route.fullPath
                    if (routePath.indexOf(".article") !== -1) {
                        app.routeEdit = 'editTrendArticle'
                        app.deleteFlag = true
                    }
                    // console.log(app.pagination);
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load data");
                });
            },
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('/api/v1/article/' + id)
                        .then(function (resp) {
                            app.rssArticles.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete");
                        });
                }
            }
        }
    }
</script>