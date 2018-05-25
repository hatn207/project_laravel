<template>
    <div class="widget">
        <div class="widget_title widget_black">
            <h2><a href="#">Tags phổ biến</a></h2>
        </div>
        <router-link v-for="row in rows" :key="row.id"  v-scroll-to="'#main-wrapper'" :to="{name: 'tagArticle', params: {slug: row.slug}}">
            <a class="btn btn-primary" style="margin:0 10px 10px 0px;">{{ row.name }} <span class="badge">{{ row.articles_count }}</span></a>
        </router-link>
        <p class="widget_divider"></p>
    </div>
</template>
<script>
export default {
    data: function () {
        return {
            rows: [],
        }
    },
    mounted() {
        var app = this;
        app.getData()
    },
    methods: {
        getData() {
            var app = this;
            axios.get('/api/v1/app-popular-tags')
            .then(function (resp) {
                app.rows = resp.data;
            })
            .catch(function (resp) {
                // console.log(resp);
                // alert("Could not load data");
            });
        }
    }
}
</script>