<template>
    <div class='noticeWarning nwDanger bodyStack' v-if="$parent.form.errors !== undefined &&$parent.form.errors[input] !== undefined" v-html="$parent.form.errors[input]" ></div>
</template>
<script>
export default {
    props: ['input'],
    install: function (Vue,options) {
        Vue.component('ajax-error', this);
    }
}
</script>
