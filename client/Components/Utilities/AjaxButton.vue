<template>
    <button  :disabled="!enabled" class="btnBlue">
        <slot></slot>
    </button>
</template>
<script>
export default {
    props: {
        form: Object
    },
    computed: {
        enabled () {
            return (this.$props.form.status === '' || 
            this.$props.form.status === 'complete' || this.$props.form.status === 'failed');
        }
    },
    install: function (Vue,options) {
        Vue.component('ajax-button', this);
    }
}
</script>
