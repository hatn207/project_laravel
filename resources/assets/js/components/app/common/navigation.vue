<template>
    <div class="navigation-section">
        <nav class="navbar m-menu navbar-default">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar-collapse-1"><span class="sr-only">Toggle navigation</span> <span
                            class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                    </button>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="#navbar-collapse-1">
                    <ul class="nav navbar-nav main-nav">
                        <router-link tag="li" :to="{name: 'home'}">
                            <a class="fa fa-home"></a>
                        </router-link>
                        <router-link tag="li" v-for="row in rows" :key="row.id" :to="{name: 'category', params: {slug: row.slug}}">
                            <a>{{ row.name }}</a>
                        </router-link>
                    </ul>
                </div>
                <!-- .navbar-collapse -->
            </div>
            <!-- .container -->
        </nav>
        <!-- .nav -->
    </div>
    <!-- .navigation-section -->
</template>

<script>
    export default {
        data: function () {
            return {
                rows: []
            }
        },
        mounted() {
            var app = this;
            app.getData()
        },
        methods: {
            // changePage(page) {
            //     var app = this;
            //     app.pagination.current_page = page;
            //     app.getRss(app.pagination.current_page);
            // },
            getData() {
                var app = this;
                axios.get('/api/v1/app-navigation')
                .then(function (resp) {
                    app.rows = resp.data;
                    // console.log(app.pagination);
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load rss");
                });
            },
            // deleteEntry(id, index) {
            //     if (confirm("Bạn có muốn xoá nó?")) {
            //         var app = this;
            //         axios.delete('/api/v1/rss/' + id)
            //             .then(function (resp) {
            //                 app.rsses.splice(index, 1);
            //             })
            //             .catch(function (resp) {
            //                 alert("Could not delete rss");
            //             });
            //     }
            // }
        }
    }
</script>