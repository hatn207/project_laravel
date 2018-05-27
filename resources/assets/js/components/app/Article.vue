<template>
    <div class="col-md-8">
    <div class="entity_wrapper">
        <div class="entity_title">
            <h1>{{ article.title }}</h1>
        </div>
        <!-- entity_title -->

        <div class="entity_meta">
            <img :src="fav_gg + article.website_url"> {{ article.website_name }}
        </div>

        <div class="entity_meta">
            Cập nhật: {{ article.updated_at }}
            <!-- , by: <a href="#">Eric joan</a> -->
        </div>

        <span class="tag orange">{{ category.name }}</span>

        <!-- <div class="entity_rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star-half-full"></i>
        </div> -->
        <!-- entity_rating -->

        <div class="entity_content">
            <figure>
                <img class="img-responsive center-block" width="40%" :src="article.image" :alt="seo.alt">
                <figcaption style="text-align: center; margin-top: 10px">Hình - {{ seo.figcaption }}</figcaption>
            </figure>
        </div>
        <!-- entity_thumb -->

        <div class="entity_content" v-html="article.headword"></div>
        <!-- entity_content -->

        <div class="entity_content" v-if="article.website_url">
            <a :href="article.website_url" class="btn btn-info" target="_blank">Đọc bài báo đầy đủ</a>
        </div>
        <!-- entity_thumb -->

        <div class="entity_footer">
            <div class="entity_tag">
                <span v-for="row in article.tags" :key="row.id" class="blank">
                    <router-link v-scroll-to="'#main-wrapper'" :to="{name: 'tagArticle', params: {slug: row.slug}}">
                        <a>{{ row.name }}</a>
                    </router-link>
                </span>
            </div>
            <!-- entity_tag -->

            <div class="entity_social">
                <span><i class="fa fa-eye"></i>{{ article.view }} lượt xem </span>
                <span><i class="fa fa-comments-o"></i>{{ comments.length }} bình luận </span>
            </div>
            <!-- entity_social -->

        </div>
        <!-- entity_footer -->

    </div>
    <!-- entity_wrapper -->

    <div class="related_news">
        <div class="entity_inner__title header_orange">
            <h2><a href="#">Cùng chuyên mục</a></h2>
        </div>
        <!-- entity_title -->

        <div class="row">
            <div class="col-md-6" v-for="row in related" :key="row.id">
                <div class="media" style="height: 130px">
                    <div class="media-left">
                        <img class="media-object" style="width: 122px; height: 110px" :src="row.thumb" :alt="row.slug">
                    </div>
                    <div class="media-body">
                        <span class="tag orange">
                            <router-link v-scroll-to="'#main-wrapper'" :to="{name: 'category', params: {id: category.id, slug: category.slug}}">
                                <a>{{ category.name }}</a>
                            </router-link>
                        </span>

                        <h3 class="media-heading">
                            <router-link v-scroll-to="'#main-wrapper'" :to="{name: 'article', params: {cate: category.slug, slug: row.slug}}">
                                <a>{{ row.title }}</a>
                            </router-link>
                        </h3>
                        <span class="media-date">
                            {{ row.updated_at }}
                        </span>

                        <div class="media_social">
                            <span><i class="fa fa-eye"></i>{{ row.view }} lượt xem </span>
                            <span><i class="fa fa-comments-o"></i>{{ row.comments.length }} bình luận </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Related news -->

    <div class="readers_comment">
        <div class="entity_inner__title header_purple">
            <h2>Đọc bình luận</h2>
        </div>
        <!-- entity_title -->

        <div class="media" v-if="!show">Chưa có bình luận...</div>

        <div class="media" v-for="comment in comments" :key="comment.id">
            <div class="media-left">
                <a href="#">
                    <img :alt="comment.name" style="width: 64px; height: 64px" class="media-object" data-src="/app/assets/img/avatarDefault.png"
                        src="/app/assets/img/avatarDefault.png" data-holder-rendered="true">
                </a>
            </div>
            <div class="media-body">
                <h2 class="media-heading">{{ comment.name }}</h2>
                <span><i class="fa fa-clock-o"></i>&nbsp;{{ comment.created_at }}</span>
                <p style="margin-top:10px">{{ comment.content }}</p>
            </div>
            
        </div>
        <!-- media end -->
    </div>
    <!--Readers Comment-->

    <div class="entity_comments">
        <div class="entity_inner__title header_black">
            <h2>Viết bình luận</h2>
        </div>
        <!--Entity Title -->

        <div class="entity_comment_from">
            <form v-on:submit="postComment()">
                <div class="form-group" :class="{'has-error' : errorsName}">
                    <input type="text" v-model="commentw.name" class="form-control" id="inputName" placeholder="Họ và tên">
                    <span v-if="errorsName" class="help-block">
                        <strong>{{nameError}}</strong>
                    </span>
                </div>
                <div class="form-group comment" :class="{'has-error' : errorsContent}">
                    <textarea class="form-control" v-model="commentw.content" id="inputComment" placeholder="Bình luận"></textarea>
                    <span v-if="errorsContent" class="help-block">
                        <strong>{{contentError}}</strong>
                    </span>
                </div>

                <button class="btn btn-submit red">Phản hồi</button>
            </form>
        </div>
        <!--Entity Comments From -->

    </div>
    <!--Entity Comments -->

    </div>
    <!--Left Section-->
</template>

<script>
export default {
    data: function () {
        return {
            comments: [],
            related: [],
            article: {},
            show: false,
            category: {},
            fav_gg: null,
            commentw: {
                name: '',
                content: '',
                article_id: 0
            },
            errorsName: false,
            nameError: null,
            errorsContent: false,
            contentError: null,
            seo: {
                titleSeo: '',
                description: '',
                keywords: ''
            }
        }
    },
    mounted() {
        var app = this;
        app.fav_gg = 'http://www.google.com/s2/favicons?domain=';
        app.getData()
    },
    metaInfo () {
        return {
            meta: [
                { name: 'keywords', content: this.seo.keywords },
                { name: 'description', content: this.seo.description }
            ],
            // if no subcomponents specify a metaInfo.title, this title will be used
            title: this.seo.titleSeo
        }
    },
    methods: {
        getData() {
            var app = this;
            var slug = app.$route.params.slug;
            axios.get('/api/v1/app-article-detail/' + slug)
            .then(function (resp) {
                app.comments = resp.data.article.comments;
                app.article = resp.data.article;
                app.category = resp.data.category;
                app.related = resp.data.related;
                app.seo = resp.data.seo;
                if (app.comments.length != 0) {
                    app.show = true;
                } else {
                    app.show = false;
                }

                // seo meta   
                app.seo.titleSeo = app.seo.title;
                app.seo.keywords = app.seo.keywords;
                app.seo.description = app.seo.description;

                app.$ga.page({
                    page: app.$route,
                    title: app.seo.title,
                    location: window.location.href
                })

                
            })
            .catch(function (resp) {
                // console.log(resp);
                alert("Could not load data");
            });
        },
        postComment() {
            event.preventDefault();
            var app = this;
            app.commentw.article_id = app.article.id;
            var newComment = app.commentw;
            app.resetForm();
            axios.post('/api/v1/comment', newComment)
                .then(function (resp) {
                    app.commentw.name = '';
                    app.commentw.content = ''
                    app.getData();
                })
                .catch(function (error) {
                    var errors = error.response
                    if(errors.statusText === 'Unprocessable Entity'){
                        if(errors.data){
                            if(errors.data.name){
                                app.errorsName = true
                                app.nameError = _.isArray(errors.data.name) ? errors.data.name[0]: errors.data.name
                            }
                            if(errors.data.content){
                                app.errorsContent = true
                                app.contentError = _.isArray(errors.data.content) ? errors.data.content[0]: errors.data.content
                            }
                        }
                    }
                    console.log(errors);
                });
        },
        resetForm () {
            var app = this;
            app.errorsName = false;
            app.nameError = null;
            app.errorsContent = false;
            app.contentError = null;
        }
    }
}
</script>
