<template>
    <div>
        <show-flag v-for="flag in flags" :flag="flag" v-bind:key="flag"/>
    </div>
</template>
<script>
import showFlag from '@C/Flag/ShowFlag'

export default {
    components: {
        showFlag
    },
    props: {
        flags: {
            type: Array,
            required: true
        }
    }
}
</script>
