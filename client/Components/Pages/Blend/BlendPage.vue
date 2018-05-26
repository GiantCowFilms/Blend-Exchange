<template>
<main-layout>
    <div slot="content">
        <modal name='flagFile' classes='modal' :pivotY='0.2' height="auto">
            <h2>
                Flag File:
            </h2>
            <create-flag-form :blend="blend"/>
        </modal>
        <div>
            <div v-if="hasVirusWarning" class="noticeWarning nwDanger bodyStack">WARNING: This blend has been reported as containing maleware. Download at your own risk. The report is unconfirmed.</div>
            <div v-if="hasCopyrightWarning" class="noticeWarning nwNotice bodyStack">NOTICE: This file has been removed on a copyright claim!</div>
        </div>
        <div class="bodyStack nwInfo noticeWarning" v-if="hasAdminComment">
            <b>Admin Comment:</b> {{blend.adminComment}}
        </div>
        <div id="fileStats" class="bodyStack contentTarget">
                <div style="text-align: center;">
                        <img class="blendDisplayIcon" src="/images/blenderFileIcon.png"/>
                        <div class="blendDisplayContainer" style="display: inline-block; margin-top: 25px; text-align: left;">
                            <h2 class="blendDisplayTitle">
                                {{ blend.fileName }}                         </h2>
                            <span class="downloadQuestionLink">
                                <a v-bind:href="blend.questionLink">View Question</a>
                                <br />
                                {{ Math.round(blend.fileSize/1024/10)/100 }} MB
                                <br />
                                {{ blend.views_count }} views <br />
                                {{ blend.downloads_count }} downloads<br />
                                {{ blend.favorites_count }} favorites
                            </span>
                        </div>
                </div>
            </div>
            <div id="favPrompt" style="display: none;" class="bodyStack noticeWarning nwInfo">
                Found this file useful? <b>Give it a favorite </b>using the button below!
            </div>
            <div class="bodyStack">
                <div id="flagBtn" class="btnBlue downloadBtnRow" v-on:click="$modal.show('flagFile')">
                    Flag
                </div><div id="favoriteBtn" class="btnBlue downloadBtnRow" v-on:click="favoriteBlend(blend.id)">
                    Favorite
                </div><div id="downloadFile" class="btnBlue downloadBtnRow" style="margin-right: 0" v-on:click="blend.downloads_count += 1">
                    <a :href="`/d/${blend.id}/${blend.fileName}`">Download</a>
                </div>
            </div>
            <h2 style="margin-top: 5px; margin-bottom: 5px;">Share this file:</h2>
            <div>Add this text into your post:</div>
            <textarea id="embedCode" class="txtBlue">[<img src="https://blend-exchange.giantcowfilms.com/embedImage.png?bid={{ blend.id }}" />](https://blend-exchange.giantcowfilms.com/b/{{ blend.id }}/)</textarea>
                        <div id="usageNotice">
                <h2>
                    Disclaimer:
                </h2>
                Download this file at your own risk. It could contain viruses or other harmful material.
                <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
            </div><br />
            <moderate-blend :blend="blend"></moderate-blend>
        </div>
</main-layout>
</template>
<script>
import axios from 'axios';
import blendExchange from '@/Api/BlendExchangeApi'
import createFlagForm from '@C/Flag/CreateFlagForm'
import moderateBlend from '@C/Blend/ModerateBlend'

export default {
    data: function () {
        return {
            blend: {
            }
        };
    },
    components: {
        createFlagForm,
        moderateBlend
    },
    computed: {
        hasVirusWarning () {
            return this.hasWarning('virus');
        },
        hasCopyrightWarning () {
            return this.hasWarning('copyright');
        },
        hasAdminComment () {
            return this.blend.adminComment !== '';
        }
    },
    async beforeRouteEnter (to, from, next) {
        try {
            var response = await blendExchange.getEndpoint(`/blends/${to.params.id}`);
            var blend = response.data;
            if (blend.id !== to.params.id) {
                next(vm => vm.$router.push({ name: 'BlendFile', params: { id: blend.id }}));
            } else {
                next(vm => vm.$data.blend = blend);
            }
        } catch (err) {
            if(!err.response) {
                throw err;
            }
            if(err.response.status == 404) {
                next(new Error('Blend file was not found.'));
            } else {
                next(new Error('An internal error occured retrieving this blend file.'));
            }
        }
    },
    methods: {
        hasWarning: function (warning) {
            return this.blend.flags && this.blend.flags.some((elem) => { return elem.offense === warning;});
        },
        favoriteBlend: function (id) {
            axios.post(`/api/blends/${id}/favorite`).then(() => {
                alert("File was successfully favorited");
                this.$data.blend.favorites_count += 1;
            }).catch(err => {
                alert(['An internal error occured']);
            });
        }
    }
}
</script>