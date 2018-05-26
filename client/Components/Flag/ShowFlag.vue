<template>
    <div>
        <h2>{{ flag.offense }}</h2>
        <p>
            {{flag.message}}
        </p>
        <a v-on:click="accept()">Accept</a> <a v-on:click="decline()">Decline</a>
    </div>
</template>
<script>
export default {
    props: {
        flag: {
            required: true,
            type: Object
        }
    }
}
</script>
 