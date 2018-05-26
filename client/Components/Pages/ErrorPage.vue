<template >
    <div class='noticeWarning nwDanger bodyStac'>
        {{ error }}
    </div>
</template>
<script>

export default {
    props: {
        error: {
            type: String,
            default: 'Page not found.'
        }
    },
    data: function () {
        return {};
    },
    methods: {

    }
}
</script>