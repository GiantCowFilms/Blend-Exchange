<template>
    <div v-bind:style="styles">
        <svg v-bind:style="{ display: 'inline-block', width: width }" version="1.1" class="spinner" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
            <polyline id="spinner1" points="0,30 0,0 60,0" fill="none" stroke="#FF782A" stroke-width="35"/>
            <polyline id="spinner2"  points="70,0 100,0 100,60" fill="none" stroke="#109DF0" stroke-width="35"/>
            <polyline id="spinner3"  points="100,70 100,100 40,100" fill="none" stroke="#109DF0" stroke-width="35"/>
            <polyline id="spinner4"  points="0,40 0,100 30,100" fill="none" stroke="#FF782A" stroke-width="35"/>
        </svg>
    </div>
</template>
<script>
export default {
    props: {
        width: {
            type: String,
            default: '32px'
        },
        center: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        styles () {
            let styles = {};
            if(this.center) {
                styles['text-align'] = 'center';
            }
            return styles;
        }
    }
}
</script>
<style>
    #spinner1 {
        opacity: .2;
        animation: cycle 1s linear 0s infinite;
    }
    #spinner2 {
        opacity: .2;
        animation: cycle 1s linear .25s infinite;
    }
    #spinner3 {
        opacity: .2;
        animation: cycle 1s linear .5s infinite;
    }
    #spinner4 {
        opacity: .2;
        animation: cycle 1s linear .75s infinite;
    }
    @keyframes cycle {
        10% { opacity: .2; }
        50% { opacity: 1; }
        70% { opacity: .2; }
    }
</style>