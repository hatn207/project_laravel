<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: routeBack}" class="btn btn-default">Quay lại</router-link>
        </div>

        <form v-on:submit="saveForm()">
        <div class="row">
            <div class="col-xs-7">
                <div class="panel panel-default">
                    <div class="panel-heading">Sửa bài viết</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group" :class="{'has-error' : errors.errorsWebsiteName}">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Tên website</label>
                                    <input type="text" v-model="rows.website_name" class="form-control">

                                    <span v-if="errors.errorsWebsiteName" class="help-block">
                                        <strong>{{errors.websiteNameError}}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Đường dẫn bài viết</label>
                                    <input type="text" v-model="rows.website_url" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" :class="{'has-error' : errors.errorsTitle}">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Tiêu đề</label>
                                    <input type="text" v-model="rows.title" class="form-control">

                                    <span v-if="errors.errorsTitle" class="help-block">
                                        <strong>{{errors.titleError}}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" :class="{'has-error' : errors.errorsImage}">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Hình ảnh</label>
                                    <input type="file" @change="onFileChange">
                                    
                                    <span v-if="errors.errorsImage" class="help-block">
                                        <strong>{{errors.imageError}}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <img :src="rows.image" width="300" alt="rss" class="img-thumbnail">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Nội dung</label>
                                    <vue-editor v-model="rows.headword" :editorToolbar="customToolbar"></vue-editor>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Xem trước</label>
                                    <p><img :src="rows.image" width="300" alt="rss" class="img-thumbnail"></p>
                                    <div v-html="rows.headword"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Danh mục</label>
                                    <select v-model="rows.category_id" class="form-control">
                                        <option v-for="category in categories" :value="category.id" :key="category.id">
                                        {{ category.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Nhãn</label>
                                    <tags-input placeholder="" class="form-control" element-id="tags" v-model="rows.selected_tags"
                                        :existing-tags="tag"
                                        :typeahead="true"></tags-input>                             
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Trạng thái</label>
                                    <select v-model="rows.status" class="form-control">
                                        <option value="1">Publish</option>
                                        <option value="2">Private</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <button class="btn btn-success">Sửa</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin SEO</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group" :class="{'has-error' : errors.errorsSeoTitle}">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Tiêu đề</label>
                                    <input type="text" v-model="rows.seo.title" class="form-control">

                                    <span v-if="errors.errorsSeoTitle" class="help-block">
                                        <strong>{{errors.seoTilteError}}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" :class="{'has-error' : errors.errorsSeoKeywords}">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Từ khóa</label>
                                    <input type="text" v-model="rows.seo.keywords" class="form-control">

                                    <span v-if="errors.errorsSeoKeywords" class="help-block">
                                        <strong>{{errors.seoKeywordsError}}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" :class="{'has-error' : errors.errorsSeoDescription}">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">Mô tả</label>
                                    <input type="text" v-model="rows.seo.description" class="form-control">

                                    <span v-if="errors.errorsSeoDescription" class="help-block">
                                        <strong>{{errors.seoDescriptionError}}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label">ALT của hình ảnh</label>
                                    <input type="text" v-model="rows.seo.alt" class="form-control">

                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        </form>    
    </div>
</template>

<script>
    import { VueEditor } from 'vue2-editor'

    export default {
        mounted() {
            var app = this;
            var id = app.$route.params.id;
            app.editID = id;
            axios.get('/api/v1/article/' + id + '/edit')
                .then(function (resp) {
                    // console.log(resp.data);
                    app.rows = resp.data.article;
                    app.$set(app.rows, 'image_change_flg', false);
                    app.categories = resp.data.categories;
                    app.tag = resp.data.tag;
                    app.$set(app.rows, 'selected_tags', resp.data.selected_tags);
                })
                .catch(function () {
                    alert("Could not load your data")
                });
        },
        components: {
            VueEditor
        },
        data: function () {
            return {
                routeBack: 'indexArticle',
                tag: {},
                editID: null,
                rows: {
                    website_name: '',
                    title: '',
                    headword: '',
                    image: '',
                    selected_tags: [],
                    // Seo todo
                    seo: {
                        title: '',
                        keywords: '',
                        description: '',
                        alt: ''
                    }
                },
                categories: {
                    name: '',
                    description: ''    
                },
                // validate errors default
                errors: {
                    errorsWebsiteName: false,
                    websiteNameError: null,
                    errorsImage: false,
                    imageError:null,
                    errorsTitle: false,
                    titleError: null,
                    // seo
                    errorsSeoTitle: false,
                    seoTilteError: null,
                    errorsSeoKeywords: false,
                    seoKeywordsError: null,
                    errorsSeoDescription: false,
                    seoDescriptionError: null
                },
                // config editor
                customToolbar: [
                    [{
                        header: [!1, 1, 2, 3, 4, 5, 6]
                    }],    
                    ["bold", "italic", "underline", "strike"],
                    [{align: ""}, {align: "center"}, {align: "right"}, {align: "justify"}],
                    ["blockquote", "code-block"],
                    [{list: "ordered"}, {list: "bullet"}],
                    [{indent: "-1"}, {indent: "+1"}],
                    [{color: []}, {background: []}],
                    ["link"]
        ]
            }
        },
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newRss = app.rows;
                axios.put('/api/v1/article/' + app.editID, newRss)
                    .then(function (resp) {
                        // console.log(resp);
                        app.$router.push({name: 'indexArticle'});
                    })
                    .catch(function (error) {
                        app.resetError();
                        var errors = error.response
                        if(errors.statusText === 'Unprocessable Entity'){
                            if(errors.data){
                                if(errors.data.website_name){
                                    app.errors.errorsWebsiteName = true
                                    app.errors.websiteNameError = _.isArray(errors.data.website_name) ? errors.data.website_name[0]: errors.data.website_name
                                }
                                if(errors.data.image){
                                    app.errors.errorsImage = true
                                    app.errors.imageError = _.isArray(errors.data.image) ? errors.data.image[0]: errors.data.image
                                }
                                if(errors.data.title){
                                    app.errors.errorsTitle = true
                                    app.errors.titleError = _.isArray(errors.data.title) ? errors.data.title[0]: errors.data.title
                                }
                            }
                        }
                        console.log(errors);
                        // alert("Could not create your rss");
                    });
            },
            onFileChange(e) {
                var app = this;
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    app.rows.image_change_flg = false;
                    return;
                }
                app.createImage(files[0]);
            },
            createImage(file) {
                var image = new Image();
                var reader = new FileReader();
                var app = this;

                reader.onload = (e) => {
                    app.rows.image = e.target.result;
                    app.rows.image_change_flg = true;
                };
                reader.readAsDataURL(file);
            },
            resetError() {
                var app = this;
                app.errors.errorsWebsiteName =  false;
                app.errors.websiteNameError =  null;
                app.errors.errorsImage =  false;
                app.errors.imageError =  null;
                app.errors.errorsTitle = false;
                app.errors.titleError = null;
                // seo
                app.errors.errorsSeoTitle = false
                app.errors.seoTilteError = null
                app.errors.errorsSeoKeywords = false
                app.errors.seoKeywordsError = null
                app.errors.errorsSeoDescription = false
                app.errors.seoDescriptionError= null
            }
            ,
            removeImage: function (e) {
                this.rows.image = '';
            }
        }
    }
</script>