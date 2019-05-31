<template>
    <div>
        <pagination :query="query" :pagination="pagination"></pagination>
        <spinner v-if="loading" :width="'64px'"></spinner>
        <template  v-else>
          <div v-for="blend in blends" :key="blend.id" class="listItem">
              <router-link :to="`/b/${blend.id}`">
              <h2>{{ blend.fileName }}</h2>
              </router-link>
              <a :href="blend.questionLink">Question</a> | {{ blend.views_count }} views | {{blend.downloads_count }} downloads | {{ blend.favorites_count }} favorites
          </div>
        </template>
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
      loading: false,
      lastRequest: undefined
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
      const requestId = Symbol();
      this.$data.lastRequest = requestId;
      var response = await blendExchange.getEndpoint(`/blends`, {
        params: query
      });
      if (this.$data.lastRequest === requestId) {
        this.loading = false;
        this.$data.blends = response.data;
        this.$data.pagination = response.meta.pagination;
      }
    }
  }
};
</script>

