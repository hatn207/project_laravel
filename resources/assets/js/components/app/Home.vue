<template>
    <div class="col-md-8" v-if="show">
    <div class="entity_wrapper">
        <div class="entity_title header_purple">
            <h1>
                <router-link :to="{name: 'home'}">
                    <a>Tin tức mới nhất</a>
                </router-link>
            </h1>
        </div>
        <!-- entity_title -->
    
        <div class="entity_thumb">
            <img class="img-responsive" width="100%" :src="rowFirst.thumb" :alt="rowFirst.slug">
        </div>
        <!-- entity_thumb -->
        
        <div class="entity_title">
            <h5>
            <router-link v-scroll-to="'#main-wrapper'" :to="{name: 'article', params: {cate: rowFirst.category.slug, slug: rowFirst.slug}}">
                <a>{{ rowFirst.title }}</a>
            </router-link>
            </h5>
        </div>
        <!-- entity_title -->

        <!-- <div class="entity_meta">
            <img :src="rowFirst.fav"> {{ rowFirst.website_name }}
        </div> -->
        <!-- entity_meta -->
        
        <!-- <div class="entity_content" v-html="rowFirst.headword"></div> -->
        <!-- entity_content -->
        

        <div class="entity_meta">
            <img :src="fav_gg + rowFirst.website_url"> {{ rowFirst.website_name }}
        </div>

        <div class="entity_meta">
            Cập nhật: {{ rowFirst.updated_at }}
            <!-- , by: <a href="#">Eric joan</a> -->
        </div>
        <!-- entity_meta -->

        <span class="tag orange">{{ rowFirst.category.name }}</span>

        <div style="margin-bottom:0px" class="entity_social">
            <span><i class="fa fa-eye"></i>{{ rowFirst.view }} lượt xem </span>
            <span><i class="fa fa-comments-o"></i>{{ rowFirst.comments_count }} bình luận </span>
        </div>
        <!-- entity_social -->
        
        <p class="widget_divider">
            <router-link v-scroll-to="'#main-wrapper'" tag="a" :to="{name: 'article', params: {cate: rowFirst.category.slug, slug: rowFirst.slug}}">
                Xem Chi tiết&nbsp;»
            </router-link>
        </p>
    
    </div>
    <!-- entity_wrapper -->
    
    <div class="row">
        <div v-for="row in rows" :key="row.id" class="col-md-6" style="margin-top: 20px; height: 450px" v-if="row.id != rowFirst.id">
            
            <div class="category_article_body">
                <div class="top_article_img">
                    <img class="img-responsive" width="100%" style="height: 220px" :src="row.thumb" :alt="row.slug">
                </div>
                <!-- top_article_img -->

                

                <div class="category_article_title">
                    <h5>
                        <router-link v-scroll-to="'#main-wrapper'" :to="{name: 'article', params: {cate: rowFirst.category.slug, slug: row.slug}}">
                            <a>{{ row.title }}</a>
                        </router-link>
                    </h5>
                </div>
                <!-- category_article_title -->
    
                <!-- <div class="category_article_content" v-html="row.headword"></div> -->
                <!-- category_article_content -->

                <div class="entity_meta">
                    <img :src="fav_gg + row.website_url"> {{ row.website_name }}
                </div>

                <div class="article_date">
                    Cập nhật: {{ row.updated_at }}
                </div>
                <!-- article_date -->

                <span class="tag orange">{{ row.category.name }}</span>
                
                <div class="article_social">
                    <span><i class="fa fa-eye"></i>{{ row.view }} lượt xem </span>
                    <span><i class="fa fa-comments-o"></i>{{ row.comments_count }} bình luận </span>
                </div>
                <!-- article_social -->

                <p class="widget_divider">
                    <router-link v-scroll-to="'#main-wrapper'" tag="a" :to="{name: 'article', params: {cate: row.category.slug, slug: row.slug}}">
                        Xem Chi tiết&nbsp;»
                    </router-link>
                </p>
            </div>
            <!-- category_article_body -->
    
        </div>
        <!-- col-md-6 -->

        
    
    </div>
    <!-- row -->
    
    <nav v-if="pagination.last_page > 1" aria-label="Page navigation" class="pagination_section">
        <ul class="pagination">
            <li v-if="pagination.current_page > 1">
                <a v-scroll-to="'#main-wrapper'" href="javascript:void(0)" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)"> <span aria-hidden="true">&laquo;</span> </a>
            </li>
            <li v-for="page in pagesNumber" :class="{'active': page == pagination.current_page}">
                <a v-scroll-to="'#main-wrapper'" href="javascript:void(0)" v-on:click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li v-if="pagination.current_page < pagination.last_page">
                <a v-scroll-to="'#main-wrapper'" href="javascript:void(0)" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)"> <span aria-hidden="true">&raquo;</span> </a>
            </li>
        </ul>
    </nav>
    <!-- navigation -->
    
    </div>
    <!-- col-md-8 -->

    <div class="col-md-8" v-else>
        <div class="entity_wrapper">
            <div class="entity_title header_purple">
                <h1>
                    <router-link :to="{name: 'category', params: {id: category.id, slug: category.slug}}">
                        <a>{{ category.name }}</a>
                    </router-link>
                </h1>
            </div>
            <!-- entity_title -->
            <div class="entity_meta">
            Không có bài viết...
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
                category: {
                },
                rowFirst: {},
                fav_gg: null,
                title: 'Health News',
                titleSeo: 'Trang tin tức sức khỏe'
            }
        },
        metaInfo () {
            return {
                meta: [
                { name: 'keywords', content: 'key1, key2' },
                { name: 'description', content: 'seo description' }
                ],
                // if no subcomponents specify a metaInfo.title, this title will be used
                title: this.title,
                // all titles will be injected into this template
                titleTemplate: '%s | '+this.titleSeo
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
            app.fav_gg = 'http://www.google.com/s2/favicons?domain=';
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
                axios.get('/api/v1/app-index' + '?page='+page)
                .then(function (resp) {
                    
                    app.rows = resp.data.data;
                    app.pagination = resp.data;
                    // app.category = resp.data.category;
                    app.rowFirst = resp.data.data[0];
                    app.$set(app.rowFirst, 'fav', 'http://www.google.com/s2/favicons?domain='+app.rowFirst.website_url);
                    // console.log(app.pagination);
                    console.log(app.rows.length);
                    if (app.rows.length != 0) {
                        app.show = true;
                    } else {
                        app.show = false;
                    }

                })
                .catch(function (resp) {
                    // console.log(resp);
                    // alert("Could not load data");
                });
            }
        }
    }
</script>