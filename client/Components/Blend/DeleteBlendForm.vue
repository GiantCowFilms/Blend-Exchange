<template>
    <div>
        <form name="deleteBlend" v-on:submit.prevent="submit(deletion,null,form)">
            <ajax-error input="reason"></ajax-error>
            <textarea v-model="deletion.reason" placeholder="Please provide a reason for the removal of this file" style="width: 100%;" rows="4">
            </textarea>
            <div v-if="deletion.reason">
                <template v-if="deletion.reason.length < 10">
                {{ 10 - deletion.reason.length }} characters to go...
                </template>
                <template v-else>
                {{ 1024 - deletion.reason.length }} characters left...
                </template>
            </div>
            <div v-else>
                10 characters to go...
            </div>
            <div class='btnBlue' v-on:click="$modal.hide('deleteBlend')">
                Cancel
            </div>
            <button class="btnBlue" >{{ deleteMode | capitalize }} Delete</button>
        </form>
    </div>
</template>
<script>
import AjaxForm from "@/Mixins/AjaxForm";

export default {
  mixins: [AjaxForm],
  props: { blend: { required: true, type: Object }, deleteMode: {required: true, type: String } },
  data() {
    return {
      deletion: {}
    };
  },
  filters: {
    capitalize: function(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  },
  mounted() {
    this.setEndpoint(`/blends/${this.blend.id}/${this.deleteMode}_delete`);
  },
  methods: {
    completedAjaxUpload: function(data) {
      this.$modal.hide("deleteBlend");
      alert([`Blend was ${this.deleteMode} deleted.`]);
    }
  }
};
</script>