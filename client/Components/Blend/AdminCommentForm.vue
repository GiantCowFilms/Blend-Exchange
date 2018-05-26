<template>
    <div>
        <form name="deleteBlend" v-on:submit.prevent="submit(comment,null,form)">
            <ajax-error input="adminComment"></ajax-error>
            <textarea v-model="comment.adminComment" placeholder="Comment." style="width: 100%;" rows="4">
            </textarea>
            <div v-if="comment.adminComment">
                <template v-if="comment.adminComment.length < 10">
                {{ 10 - comment.adminComment.length }} characters to go...
                </template>
                <template v-else>
                {{ 1024 - comment.adminComment.length }} characters left...
                </template>
            </div>
            <div v-else>
                10 characters to go...
            </div>
            <div class='btnBlue' v-on:click="$modal.hide('adminComment')">
                Cancel
            </div>
            <button class="btnBlue" >Comment</button>
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
      comment: {
          adminComment: ''
      }
    };
  },
  mounted() {
    this.comment.adminComment = this.blend.adminComment;
    this.setEndpoint(`/blends/${this.blend.id}/admin_comment`);
  },
  methods: {
    completedAjaxUpload: function(data) {
      this.blend.adminComment = this.comment.adminComment;
      this.$modal.hide("adminComment");
      alert([`Comment was posted`]);
    }
  }
};
</script>