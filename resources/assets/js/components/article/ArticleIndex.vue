<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createArticle'}" class="btn btn-success">Tạo mới</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Danh sách</div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-3 form-group">
                            <label class="control-label">Liệt kê theo danh mục</label>
                            <select v-model="searchs.categorySelected" class="form-control" @change="onCateChange">
                                <option value="0">Tất cả danh mục</option>
                                <option v-for="category in categories" :value="category.id" :key="category.id">
                                {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-xs-3 form-group">
                            <label class="control-label">Liệt kê theo trạng thái</label>
                            <select style="width: 150px" v-model="searchs.statusSelected" class="form-control" @change="onCateChange">
                                <option value="0">Tất cả trạng thái</option>
                                <option value="1">Publish</option>
                                <option value="2">Private</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div v-if="!show">
                    <p>Không có bài viết</p>
                </div>
                <div class="table-responsive" v-if="show">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <!-- <th>Tên website</th> -->
                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>
                            <th>Ngày tạo</th>
                            <th width="90">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in rows">
                            <td>{{ index+1 }}</td>
                            <!-- <td>{{ row.website_name }}</td> -->
                            <td><a :href="row.website_url" target="_blank">{{ row.title }}</a></td>
                            <td><img width="150" :src="row.thumb" /></td>
                            <td>{{ row.created_at }}</td>
                            <td>
                                <router-link :to="{name: 'editArticle', params: {id: row.id}}" class="btn btn-xs btn-primary">
                                    Sửa
                                </router-link>
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
                categories: [],
                searchs: {
                    categorySelected: 0,
                    statusSelected: 0,
                }
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
                var newSearchs = app.searchs;
                var searchQuery = '&statusSelected='+newSearchs.statusSelected;
                axios.get('/api/v1/article/search/' + newSearchs.categorySelected + '?page='+page+searchQuery)
                .then(function (resp) {
                    // console.log(resp.data.articles.data);
                    app.rows = resp.data.articles.data;
                    app.pagination = resp.data.articles;
                    app.categories = resp.data.categories;
                    // console.log(app.pagination);
                    if (app.rows.length != 0) {
                        app.show = true;
                    } else {
                        app.show = false;
                    }

                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load data");
                });
            },
            onCateChange () {
                var app = this;
                app.getData(app.pagination.current_page);
            },
            deleteEntry(id, index) {
                if (confirm("Bạn có muốn xoá nó?")) {
                    var app = this;
                    axios.delete('/api/v1/article/' + id)
                        .then(function (resp) {
                            app.rows.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete");
                        });
                }
            }
        }
    }
</script>