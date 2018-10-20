<template>
    <div>
        <div id="outerContainer">
            <main-header></main-header>
            <div id="mainContainer">
                <slot name='content'>

                </slot> 
            </div>
        </div>
    </div>
</template>
<script>
import MainHeader from './MainHeader'
export default {
    components: {
        MainHeader
    }
}
</script>

