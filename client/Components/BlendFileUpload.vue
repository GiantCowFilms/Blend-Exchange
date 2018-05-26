<template>
<main-layout>
    <div slot="content">
    <ajax-form name="blendUpload" action='/blends/create' v-on:completedAjaxUpload="completedAjaxUpload" >
        <template slot-scope="ajax">
        <ajax-error input="blendFile"></ajax-error>
        <div id="uploadTarget" class="bodyStack contentTarget">
        <input id="uploadDrop" type="file" name="blendFile" @change="ajax.methods.setFile($event.target.name, $event.target.files)"/>
                <div id="uploadText">
                    <div class="centerText" v-if="typeof ajax.form.blendFile === 'undefined'">
                        Drag a file here to upload a .blend<br>or click to browse
                    </div>
                    <div id="uploadFileCard" v-if="typeof ajax.form.blendFile !== 'undefined'"> 
                        <h2>{{ajax.form.blendFile.name}}</h2>
                        <b>{{(ajax.form.blendFile.size/1024/1000).toFixed(1)}}</b> MB
                        <div class="progressContainer">
                            <div class="progress" :style="{width: ajax.meta.uploadProgress + '%'}"></div>
                        </div>
                        <div>Files may take some time to process</div>
                    </div>
                </div>
                </div>
                <div class="bodyStack">
                <p>
<b>Terms of Service:</b></p>
                <div id="privacyAgreements">
                    <ajax-error input="termsAndPrivacy"></ajax-error>
                    <div class="bodyStack"><input v-model="ajax.form.termsAndPrivacy" name="termsAndPrivacy" type="checkbox">I have read agree to the Blend-Exchange <a href="/terms">Terms of Service</a> and have read the <a href="/privacy">Privacy Policy</a>. </div>            
                    <ajax-error input="certifyUnderstanding"></ajax-error>
                    <div class="bodyStack"><input v-model="ajax.form.certifyUnderstanding" name="certifyUnderstanding" type="checkbox" />I understand the irrevocable rights to my content (including but not limited to royalty free distrbution) I grant Blend-Exchange as defined in the <a href="/terms">Terms of Service</a>.</div>
                    </div>
  Please see the section titiled "User Content" in the <a href="/terms">Terms of Service</a> for more information on the rights you give blend-exchange when you upload a .blend file.
            </div>
                <ajax-error input="questionLink"></ajax-error>
    <input class="txtBlue bodyStack" v-model="ajax.form.questionLink" id="questionLink" placeholder="Enter the url of the question on blender.stackexchange" value="" />
    <button id="upload" class="btnBlue bodyStack">
        Upload
    </button>
        </template>
    </ajax-form>
                <div id="usageNotice">
                <h2>
                    Notice:
                </h2>
                This service is for blender.stackexchange questions and answers only. Files for other uses will be removed. The purpose of this site is to allow users to upload .blends for their blender.stackexchange posts. Please flag all violating posts.
            </div><br />
            <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
    </div>
</main-layout>
</template>
<script>
import axios from 'axios';

export default {
    data: function () {
        return {
            blendData: {
                questionLink: '',
            },
            errors: {}
        }
    },
    methods: {
        setFile (fieldName,files) {
            this.$set(this.blendData,'blendFile',files[0]);
        },
        completedAjaxUpload: function (name,data) {
            if (name === 'blendUpload') {
                this.$router.push({ name: 'BlendPage', params: { id: data.id }});
            }
        }
    }
}
</script>