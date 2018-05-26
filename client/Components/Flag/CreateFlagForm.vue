<template>
    <div>
        <form name="flagFile" v-on:submit.prevent="submit(flag,null,form)">
            <ajax-error input="offense"></ajax-error>
            <input type="radio" v-model="flag.offense" value="virus">Virus<br>
            <input type="radio" v-model="flag.offense"  value="copyright">Copyright Offense<br>
            <input type="radio" v-model="flag.offense" value="notSE">Abuse of Service: Not used on the linked blender.stackexchange question<br>
            <input type="radio" v-model="flag.offense" value="spam">Spam<br>
            <input type="radio" v-model="flag.offense" value="obscene">Obscene: Contains non family friendly content<br>
            <input type="radio" v-model="flag.offense" value="other">Other<br>
            <ajax-error input="message"></ajax-error>
            <textarea v-model="flag.message" placeholder="Please describe the issue" style="width: 100%;" rows="4">
            </textarea>
            <div v-if="flag.message">
                <template v-if="flag.message.length < 10">
                {{ 10 - flag.message.length }} characters to go...
                </template>
                <template v-else>
                {{ 1024 - flag.message.length }} characters left...
                </template>
            </div>
            <div v-else>
                10 characters to go...
            </div>
            <div class='btnBlue' v-on:click="$modal.hide('flagFile')">
                Cancel
            </div>
            <button class='btnBlue'>
                Flag
            </button>
        </form>
    </div>
</template>
<script>
import AjaxForm from "@/Mixins/AjaxForm";

export default {
  mixins: [AjaxForm],
  props: { blend: { required: true, type: Object } },
  data() {
    return {
      flag: {}
    };
  },
  mounted() {
    this.setEndpoint(`/blends/${this.blend.id}/flag`);
  },
  methods: {
    completedAjaxUpload: function(data) {
        this.$modal.hide("flagFile");
        alert(["File was flagged. Thank you for helping a community project."]);
    }
  }
};
</script>