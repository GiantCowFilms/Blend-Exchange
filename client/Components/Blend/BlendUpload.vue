<template>
    <div>
    <form v-on:submit.prevent="submit(blend,files,form)">
        <ajax-error input="blendFile"></ajax-error>
        <div id="uploadTarget" class="bodyStack contentTarget" v-on:dragenter="fileHover++" v-on:dragleave="fileHover--" v-on:drop="fileHover--" :class="{ dragHover: fileHover > 0 }">
        <input id="uploadDrop" type="file" name="blendFile" @change="setFile(form, files, $event.target.name, $event.target.files)"/>
                <div id="uploadText">
                    <div class="centerText" v-if="typeof files.blendFile === 'undefined'">
                        Drag a file here to upload a .blend<br>or click to browse
                    </div>
                    <div id="uploadFileCard" v-if="typeof files.blendFile !== 'undefined'">
                        <h2>{{ files.blendFile.name }}</h2>
                        <div v-if="(form.status == 'pending' || form.status == 'completed') && form.uploadProgress['blendFile'] == 100">
                            <spinner style="display: inline-block;vertical-align: middle;"></spinner> <b>Processing...</b>
                        </div>
                        <div v-else>
                            <b>{{(files.blendFile.size/1024/1000).toFixed(1)}}</b> MB
                            <div class="progressContainer">
                                <div class="progress" :style="{width: form.uploadProgress['blendFile'] + '%'}"></div>
                            </div>
                            <div>Files may take some time to process</div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="bodyStack">
                <p>
<b>Terms of Service:</b></p>
                <div id="privacyAgreements">
                    <ajax-error input="termsAndPrivacy"></ajax-error>
                    <div class="bodyStack"><input v-model="blend.termsAndPrivacy" name="termsAndPrivacy" type="checkbox">I have read agree to the Blend-Exchange <a href="/terms">Terms of Service</a> and have read the <a href="/privacy">Privacy Policy</a>. </div>            
                    <ajax-error input="certifyUnderstanding"></ajax-error>
                    <div class="bodyStack"><input v-model="blend.certifyUnderstanding" name="certifyUnderstanding" type="checkbox" />I understand the irrevocable rights to my content (including but not limited to royalty free distrbution) I grant Blend-Exchange as defined in the <a href="/terms">Terms of Service</a>.</div>
                    </div>
  Please see the section titiled "User Content" in the <a href="/terms">Terms of Service</a> for more information on the rights you give blend-exchange when you upload a .blend file.
            </div>
            <div class="nwInfo noticeWarning bodyStack" v-if="isAuthenticated">
                You are logged in as <b>{{ currentUser.username }}</b>. Uploaded blends will be attached to your account.
            </div>

                <ajax-error input="questionLink"></ajax-error>
    <input class="txtBlue bodyStack" type="text" v-model="blend.questionLink" id="questionLink" placeholder="Enter the url of the question on blender.stackexchange" />
    <button id="upload" class="btnBlue bodyStack">
        Upload
    </button>
    </form>
                <div id="usageNotice">
                <h2>
                    Notice:
                </h2>
                This service is for blender.stackexchange questions and answers only. Files for other uses will be removed. The purpose of this site is to allow users to upload .blends for their blender.stackexchange posts. Please flag all violating posts.
            </div><br />
            <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
    </div>
</template>
<script>
import ajaxForm from '@/Api/AjaxForm.js'
import AjaxForm from '@/Mixins/AjaxForm.vue'
import { mapGetters } from 'vuex'
import embedTextGenerator from '@/Api/embedTextGenerator.js'
export default {
    mixins: [AjaxForm],
    data () {
        return {
            fileHover: 0,
            blend: {}
        }
    },
    mounted () {
        this.setEndpoint('/blends/create');
        this.$set(this.blend,'questionLink',this.$route.query.qurl);
    },
    computed: {
                ...mapGetters([
            'currentUser',
            'isAuthenticated'
        ])
    },
    methods: {
        completedAjaxUpload (data) {
            window.parent.postMessage({ name: "embedSource", content: embedTextGenerator(data) }, "*");
            //Alert for popup
            if (window.opener != null && !window.opener.closed) {
                window.opener.postMessage({ name: "embedSource", content:  embedTextGenerator(data)  }, "*");
            }

            this.$router.push({ name: 'BlendPage', params: { id: data.id }});
        },
    }
}
</script>
