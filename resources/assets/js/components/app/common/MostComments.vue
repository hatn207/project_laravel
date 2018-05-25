<template>
    <div class="widget">
        <div class="widget_title widget_black">
            <h2><a href="#">Bình luận</a></h2>
        </div>
        <div class="media" v-for="row in rows" :key="row.id">
            <div class="media-left">
                <img class="media-object" style="width: 122px; height: 100px" :src="row.thumb" :alt="row.slug">
            </div>
            <div class="media-body">
                <h3 class="media-heading">
                    <router-link v-scroll-to="'#main-wrapper'" :to="{name: 'article', params: {cate: row.category.slug, slug: row.slug}}">
                        <a>{{ row.title }}</a>
                    </router-link>
                </h3> 
                <span class="media-date">
                    {{ row.updated_at }}
                </span>
    
                <div class="widget_article_social">
                    <!-- <span><i class="fa fa-eye"></i>{{ row.view }} lượt xem </span> -->
                    <span><i class="fa fa-comments-o"></i>{{ row.comments_count }} bình luận </span>
                </div>
            </div>
        </div>
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
            axios.get('/api/v1/app-most-comments')
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