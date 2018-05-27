
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//scrollto
var VueScrollTo = require('vue-scrollto');
Vue.use(VueScrollTo)

// You can also pass in the default options
Vue.use(VueScrollTo, {
     container: "body",
     duration: 500,
     easing: "ease-in-out",
     offset: 0,
     cancelable: true,
     onStart: false,
     onDone: false,
     onCancel: false,
     x: false,
     y: true
 })

// google analytics
import VueAnalytics from 'vue-analytics'

//truncate
var VueTruncate = require('vue-truncate-filter')
Vue.use(VueTruncate)

//input tags
Vue.component('tags-input', require('@voerro/vue-tagsinput').default);
Vue.config.keyCodes.backspace = 8;

// import route
import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

// import meta
import Meta from 'vue-meta';
Vue.use(Meta);

// Rss
import RssIndex from './components/rss/RssIndex.vue';
import RssCreate from './components/rss/RssCreate.vue';

// rss article
import RssArticleIndex from './components/rss_article/RssArticleIndex.vue';
import RssArticleEdit from './components/rss_article/RssArticleEdit.vue';
// import RssAritcleCreate from './components/rss/RssCreate.vue';
// import VuePagination from './components/Pagination.vue';
// import CompaniesCreate from './components/companies/CompaniesCreate.vue';
// import CompaniesEdit from './components/companies/CompaniesEdit.vue';

//category
import CategoryIndex from './components/category/CategoryIndex.vue';
import CategoryCreate from './components/category/CategoryCreate.vue';
import CategoryEdit from './components/category/CategoryEdit.vue';

//trend
import TrendIndex from './components/trend/TrendIndex.vue';
import TrendCreate from './components/trend/TrendCreate.vue';
import TrendShow from './components/trend/TrendShow.vue';
import TrendArticleEdit from './components/trend/TrendArticleEdit.vue';
import TrendRssArticleEdit from './components/trend/TrendRssArticleEdit.vue';

//article
import ArticleIndex from './components/article/ArticleIndex.vue';
import ArticleEdit from './components/article/ArticleEdit.vue';
import ArticleCreate from './components/article/ArticleCreate.vue';

// loading
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue'));

// app
// nav
Vue.component('navigation', require('./components/app/common/navigation.vue'));
// Widget Right
Vue.component('widgetright', require('./components/app/common/WidgetRight.vue'));
// popularnews
Vue.component('popularnews', require('./components/app/common/PopularNews.vue'));
// mostcomments
Vue.component('mostcomments', require('./components/app/common/MostComments.vue'));
// populartags
Vue.component('populartags', require('./components/app/common/PopularTags.vue'));

import Home from './components/app/Home.vue';
import Category from './components/app/Category.vue';
import Article from './components/app/Article.vue';
import TagArticle from './components/app/TagArticle.vue';

const routes = [
    //rss
    {
        path: '/admin/rss/list',
        components: {
            rssIndex: RssIndex
        },
        name: 'indexRss'
    },
    {path: '/admin/rss/rss-create', component: RssCreate, name: 'createRss'},
    // {path: '/admin/companies/edit/:id', component: CompaniesEdit, name: 'editCompany'},
    //rss article
    {
        path: '/admin/rss-article/list',
        components: {
            rssArticleIndex: RssArticleIndex
        },
        name: 'indexRssArticle'
    },
    {path: '/admin/rss-article/edit/:id', component: RssArticleEdit, name: 'editRssArticle'},
    // category
    {
        path: '/admin/category/list',
        components: {
            categoryIndex: CategoryIndex
        },
        name: 'indexCategory'
    },
    {path: '/admin/category/create', component: CategoryCreate, name: 'createCategory'},
    {path: '/admin/category/edit/:id', component: CategoryEdit, name: 'editCategory'},
    // trend
    {
        path: '/admin/trend/list',
        components: {
            trendIndex: TrendIndex
        },
        name: 'indexTrend'
    },
    {path: '/admin/trend/create', component: TrendCreate, name: 'createTrend'},
    {path: '/admin/trend/:slug', component: TrendShow, name: 'showTrend'},
    {path: '/admin/trend/edit/:id', component: TrendArticleEdit, name: 'editTrendArticle'},
    {path: '/admin/trend/edit/:id', component: TrendRssArticleEdit, name: 'editTrendRssArticle'},
    
    // article
    {
        path: '/admin/article/list',
        components: {
            articleIndex: ArticleIndex
        },
        name: 'indexArticle'
    },
    {path: '/admin/article/edit/:id', component: ArticleEdit, name: 'editArticle'},
    {path: '/admin/article/create', component: ArticleCreate, name: 'createArticle'},

    //app
    {
        path: '/',
        components: {
            home: Home
        },
        name: 'home',
        // children: [
        //     {path: ':id/:slug', component: Category, name: 'category'}
        // ]
    },
    {path: '/:slug', component: Category, name: 'category'},
    {path: '/:cate/:slug', component: Article, name: 'article'},
    {path: '/tags/:slug', component: TagArticle, name: 'tagArticle'},
    
]

const router = new VueRouter({ routes, linkActiveClass: 'active', mode: 'history'})

const app = new Vue({ router }).$mount('#main-wrapper')
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });
