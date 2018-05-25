<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'indexRss'}" class="btn btn-default">Quay lại</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Tạo mới</div>
            <div class="panel-body">
                <pulse-loader :loading="loading"></pulse-loader>
                <form v-on:submit="saveForm()">
                    <div class="row">
                        <div class="form-group" :class="{'has-error' : errorsWebsiteUrl}">
                            <div class="col-xs-12 form-group">
                                <label class="control-label">Đường dẫn Rss</label>
                                <input type="text" v-model="rss.website_url" class="form-control">

                                <span v-if="errorsWebsiteUrl" class="help-block">
                                    <strong>{{websiteurlError}}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <button class="btn btn-success" :disabled="loading">Tạo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                rss: {
                    website_url: '',
                },
                errorsWebsiteUrl: false,
                websiteurlError: null,
                loading: false
            }
        },
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newRss = app.rss;
                app.loading = true;
                axios.post('/api/v1/rss', newRss)
                    .then(function (resp) {
                        app.$router.push({name: 'indexRss'});
                        app.loading = false;
                    })
                    .catch(function (error) {
                        var errors = error.response
                        if(errors.statusText === 'Unprocessable Entity'){
                            if(errors.data){
                                if(errors.data.website_url){
                                    app.errorsWebsiteUrl = true
                                    app.websiteurlError = _.isArray(errors.data.website_url) ? errors.data.website_url[0]: errors.data.website_url
                                }
                                if(errors.data.msg){
                                    app.errorsWebsiteUrl = true
                                    app.websiteurlError = errors.data.msg
                                }
                            }
                        }
                        console.log(errors);
                        // alert("Could not create your rss");
                        app.loading = false;
                    });
            }
        }
    }
</script>