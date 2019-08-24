<template>
    <div>
        <h2>{{ flag.offense }}</h2>
        <p>
            {{flag.message}}
        </p>
        <i>
            {{ acceptance }}
        </i><br>
        <a v-on:click="accept">Accept</a> <a v-on:click="decline">Decline</a>
    </div>
</template>
<script>
import blendExchange from "@/Api/BlendExchangeApi.js";
export default {
    props: {
        flag: {
            required: true,
            type: Object
        }
    },
    computed: {
        acceptance () {
            return this.$props.flag.accepted === 0 ? "unreviewed" : (this.$props.flag.accepted === 1 ? 'accepted' : 'declined');
        }
    },
    methods: {
        async accept () {
            const response = await blendExchange.setEndpoint(`/flag/${this.$props.flag.id}/accept`,{

            });
            if (response.type === "success") {
                alert(["Flag Accepted"]);
            }
        },
        async decline () {
            const response = await blendExchange.setEndpoint(`/flag/${this.$props.flag.id}/decline`,{

            });
            if (response.type === "success") {
                alert(["Flag Declined"]);
            }
        }
    }
}
</script>
 