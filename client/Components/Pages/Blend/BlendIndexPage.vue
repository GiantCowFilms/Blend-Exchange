<template>
  <main-layout>
    <div slot="content">
        <div>
          Sort: <select v-model="query.sort">
            <option>date</option>
            <option>views</option>
            <option>downloads</option>
          </select>
        </div>
        <index-blends :query="query"></index-blends>
        <!--<div v-for="blend in blends" :key="blend.id">
          <a :href="`/b/${blend.id}`">
            <h3>{{ blend.fileName }}</h3>
          </a>
        </div>-->
    </div>
  </main-layout>
</template>
<script>
import blendExchange from '@/Api/BlendExchangeApi'
import queryString from 'query-string'
import IndexBlends from '@C/Blend/IndexBlends'
export default {
  components: {
    IndexBlends
  },
  data: function() {
    return {
      query: {

      },
      blends: []
    };
  },
  watch: {
    query: {
      handler: function (newQuery) {
        history.pushState({},null,this.$route.path  + '?' + queryString.stringify(newQuery));
      },
      deep: true
    }
  },
  async beforeRouteEnter(to, from, next) {
    try {
      var query =  Object.assign({
        sort: 'date',
        page: 1,
      },to.query);
      next(vm => {
        vm.$data.query = query
      });
    } catch (err) {
      if (!err.response) {
        throw err;
      }
      next(new Error("An internal error occured retriving the blends list."));
    }
  }
};
</script>

