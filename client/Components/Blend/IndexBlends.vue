<template>
    <div>
        <pagination :query="query" :pagination="pagination"></pagination>
        <spinner v-if="loading" :width="'64px'"></spinner>
        <div v-if="!loading" v-for="blend in blends" :key="blend.id" class="listItem">
            <router-link :to="`/b/${blend.id}`">
            <h2>{{ blend.fileName }}</h2>
            </router-link>
            <a :href="blend.questionLink">Question</a> | {{ blend.views_count }} views | {{blend.downloads_count }} downloads | {{ blend.favorites_count }} favorites
        </div>
    </div>
</template>
<script>
import blendExchange from "@/Api/BlendExchangeApi";
import Pagination from "@C/Pagination/Pagination";

export default {
  components: {
    Pagination
  },
  props: {
    query: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      blends: [],
      pagination: {},
      loading: false
    };
  },
  watch: {
    query: {
      handler: function(newQuery) {
        this.updateBlends(newQuery);
      },
      deep: true
    }
  },
  mounted () {
    this.updateBlends(this.query);
  },
  methods: {
    updateBlends: async function(query) {
      this.loading = true;
      var response = await blendExchange.getEndpoint(`/blends`, {
        params: query
      });
      this.loading = false;
      this.$data.blends = response.data;
      this.$data.pagination = response.meta.pagination;
    }
  }
};
</script>

