<template>
    <main-layout>
        <div slot="content">
            Blend-Exchange is currently using {{ about.used.toFixed(1) }} GB of its {{ about.total.toFixed(1) }} GB of available space, {{ about.percent.toFixed(1) }}%
            <div class="progressContainer">
                <div class="progress" :style="{ width: about.percent + '%' }"></div>
            </div>
            <h1>About:</h1>
            Blend-Exchange is a free blend hosting site for the users of blender.stackexchange.com. It is operated by members of the community.
            <h2>Contributions</h2>
            <a href="http://blender.stackexchange.com/users/3127/giantcowfilms">GiantCowFilms:</a> Server administration, lead development, and some assistance with the userscript<br />
            <a href="http://blender.stackexchange.com/users/599/gandalf3">gandalf3:</a> Lead development on the userscript, design, and branding, minor development, and content editing.<br />
            In addition, I would like to thank <a href="http://stackoverflow.com/users/201789/tehshrike">TehShrike</a> the database wizard for his help on the first version of the site.
            <h1>
                Faq:
            </h1>
            <h2>Why only blender.stackexchange?</h2>
            We have limited server space, and in order to have enough long into the future we need to limit the usage.
            <h2>Who runs this?</h2>
            This crazy person:<br><br>
            <a href="http://blender.stackexchange.com/users/3127/giantcowfilms">
                <img src="http://blender.stackexchange.com/users/flair/3127.png" width="208" height="58" alt="profile for GiantCowFilms at Blender Stack Exchange, Q&amp;A for people who use Blender to create 3D graphics, animations, or games" title="profile for GiantCowFilms at Blender Stack Exchange, Q&amp;A for people who use Blender to create 3D graphics, animations, or games">
            </a>
        </div>
    </main-layout>
</template>
<script>
import blendExchange from '@/Api/BlendExchangeApi'

export default {
    data () {
        return {
            about: {}
        };
    },

    async beforeRouteEnter (to, from, next) {
        var response = await blendExchange.getEndpoint(`/about`);
        next(vm => vm.$data.about = response.data);
    }
}
</script>
