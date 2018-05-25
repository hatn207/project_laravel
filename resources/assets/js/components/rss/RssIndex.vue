<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createRss'}" class="btn btn-success">Tạo mới</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Danh sách</div>
            <div class="panel-body">
                <div class="table-responsive" v-if="show">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tiêu đề</th>
                            <th>Đường dẫn</th>
                            <th>Ngày tạo</th>
                            <th width="100">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(rss, index) in rsses">
                            <td>{{ index+1 }}</td>
                            <td>{{ rss.title }}</td>
                            <td>{{ rss.website_url }}</td>
                            <td>{{ rss.created_at }}</td>
                            <td>
                                <!-- <router-link :to="{name: 'editRss', params: {id: rss.id}}" class="btn btn-xs btn-default">
                                    Edit
                                </router-link> -->
                                <a href="#"
                                class="btn btn-xs btn-danger"
                                v-on:click="deleteEntry(rss.id, index)">
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
                rsses: [],
                counter: 0,
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                show: false
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
                axios.get('/api/v1/rss?page='+page)
                .then(function (resp) {
                    app.rsses = resp.data.data;
                    app.pagination = resp.data;
                    // console.log(app.pagination);
                    if (app.rsses.length != 0) {
                        app.show = true;
                    }
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load rss");
                });
            },
            deleteEntry(id, index) {
                if (confirm("Bạn có muốn xoá nó?")) {
                    var app = this;
                    axios.delete('/api/v1/rss/' + id)
                        .then(function (resp) {
                            app.rsses.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete rss");
                        });
                }
            }
        }
    }
</script>