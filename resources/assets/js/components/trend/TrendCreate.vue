<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'indexTrend'}" class="btn btn-default">Quay lại</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Tạo mới</div>
            <div class="panel-body">
                <pulse-loader :loading="loading"></pulse-loader>
                <form v-on:submit="saveForm()">
                    <div class="row">
                        <div class="form-group" :class="{'has-error' : errorsName}">
                            <div class="col-xs-12 form-group">
                                <label class="control-label">Nội dung xu hướng</label>
                                <input type="text" v-model="row.content" class="form-control">

                                <span v-if="errorsName" class="help-block">
                                    <strong>{{nameError}}</strong>
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
                row: {
                    content: '',
                },
                errorsName: false,
                nameError: null,
                loading: false
            }
        },
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newRow = app.row;
                app.loading = true;
                axios.post('/api/v1/trend', newRow)
                    .then(function (resp) {
                        app.$router.push({name: 'indexTrend'});
                        app.loading = false;
                        // console.log(resp);
                    })
                    .catch(function (error) {
                        var errors = error.response
                        if(errors.statusText === 'Unprocessable Entity'){
                            if(errors.data){
                                if(errors.data.content){
                                    app.errorsName = true
                                    app.nameError = _.isArray(errors.data.content) ? errors.data.content[0]: errors.data.content
                                }
                                if(errors.data.msg){
                                    app.errorsName = true
                                    app.nameError = errors.data.msg
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