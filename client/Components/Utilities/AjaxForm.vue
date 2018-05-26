<template>
    <form :id="id" :class="cssClass" :name="name" :action="action" :method="method" v-on:submit.prevent="beginAjaxUpload">
        <div v-if="serverError" class="noticeWarning nwDanger bodyStack">
            {{ serverError }}
        </div>
        <slot :form="postData" :meta="meta" :methods="{ setFile }">

        </slot>
    </form>
</template>
<script>
import axios from 'axios';
import AjaxFormError from './AjaxFormError'
import blendExchange from '@/Api/BlendExchangeApi'
export default {
    props: {
        action: {
            required: true,
            type: String
        },
        id: String,
        cssClass: String,
        method: {
            type: String,
            default: 'POST'
        },
        name : {
            required: true,
            type: String
        }
    },
    data: function () {
        return {
            serverError: false,
            errors: {
            },
            postData: {
                fileNames: {}
            },
            meta: {
                uploadProgress: 0
            }
        };
    },
    methods: {
        serializeFormData (formData) {
            var postData = this.$data.postData;
            for ( var key in postData ) {
                if (postData[key] instanceof File) {
                    formData.append(key, postData[key], postData[key].name);
                } else {
                    formData.append(key, postData[key]);
                };
            }
        },
        setFile (name,files) {
            this.$set(this.$data.postData,name,files[0]);
            this.$set(this.$data.postData.fileNames,name,files[0].name);
        },
        async uploadFiles (action,uploadFiles,modification_token) {
            for (let [key,file] of Object.entries(uploadFiles)) {
                await axios.post(action,file,{
                    onUploadProgress: pe => { this.$emit('ajaxFileUploadProgress', this.$props.name, pe); this.$data.meta.uploadProgress = (pe.loaded/pe.total) * 100; console.log(pe)},
                    headers: {
                        'X-Resource-Token': `${modification_token}`,
                        'Content-Type': 'application/octet-stream'
                    }
                });
            }
        },
        async uploadForm (action,uploadData) {
            return await blendExchange.setEndpoint(this.$props.action,uploadData,{
                onUploadProgress: pe => { this.$emit('ajaxUploadProgress', this.$props.name, pe); this.$data.meta.uploadProgress = (pe.loaded/pe.total) * 100; console.log(pe)}
            });
        },
        async beginAjaxUpload () {
            try {
                var result = await blendExchange.submitForm({
                    submissionData: this.$data.postData,
                    metaData: {},
                    endpoint: this.$props.action,
                    options: {
                        fileUploadProgress:  pe => { this.$emit('ajaxUploadProgress', this.$props.name, pe); this.$data.meta.uploadProgress = (pe.loaded/pe.total) * 100; console.log(pe)}
                    }
                });
                this.$emit('completedAjaxUpload',this.$props.name, result);
            } catch (err) {
                if (typeof err.response === 'undefined') {
                    throw err;
                }
                if (err.response.status !== 422) {
                    if (err.response.data.type === 'error') {
                        this.$data.serverError = err.response.data.error;
                    } else {
                        this.$data.serverError = 'There was an internal error attempting to complete your request.';
                    }

                } else {
                    this.$data.serverError = err.response.data.error;
                    this.$data.errors = err.response.data.errors;
                }
            }

            return;
            this.$data.serverError = false;
            var uploadData = {};
            for (let field of Object.keys(this.$data.postData)) {
                if (!(this.$data.postData[field] instanceof File)) {
                    uploadData[field] = this.$data.postData[field]
                }
            }
            var uploadFiles = {};
            for (let field of Object.keys(this.$data.postData)) {
                if (this.$data.postData[field] instanceof File) {
                    uploadFiles[field] = this.$data.postData[field]
                }
            }
            try {
                var data = await this.uploadForm(this.$props.action,uploadData);
                if( 
                    typeof data !== 'undefined' && 
                    typeof data._meta !== 'undefined'&&
                    data._meta.type === 'requires_modification'
                ) {
                    await this.uploadFiles(data._meta.endpoint,uploadFiles,data._meta.modification_token);
                }
                this.$emit('completedAjaxUpload',this.$props.name, data);
            } catch (err) {
                if (typeof err.response === 'undefined') {
                    throw err;
                }
                if (err.response.status !== 422) {
                    if (err.response.data.type === 'error') {
                        this.$data.serverError = err.response.data.error;
                    } else {
                        this.$data.serverError = 'There was an internal error attempting to complete your request.';
                    }

                } else {
                    this.$data.serverError = err.response.data.error;
                    this.$data.errors = err.response.data.errors;
                }
            }
        }
    },
    install: function (Vue,options) {
        Vue.component('ajax-form', this);
    }
}
</script>