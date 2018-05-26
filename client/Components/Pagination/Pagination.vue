<template>
    <div class="paginator">
        <a v-on:click="setPage(1)">First </a>
        <a v-on:click="setPage(page - 1)"><< </a>
        <a v-for="p in pages" :key="p" :class="{ currentPage: (p === page) }" v-on:click="setPage(p)">
            {{ p }}
        </a>
        <a v-on:click="setPage(page + 1)"> >></a><a v-on:click="setPage(lastPage)"> Last</a>
    </div>
</template>
<script>
export default {
    props: {
        query: {
            type: Object,
            required: true
        },
        pagination: {
            type: Object,
            required: true
        }
    },
    computed: {
        page () {
            return +this.query.page;
        },
        lastPage () {
            return this.pagination.total_pages;
        },
        pages () {
            let pages = [];
            for (
                let i = Math.max(1,Math.min(this.page - 2,this.lastPage -5));
                i <= Math.min(this.lastPage,Math.max(this.page + 2,5));
                i++
            ) {
                pages.push(i);
            }
            return pages;
        }
    },
    methods: {
        setPage (p) {
            p = Math.min(Math.max(1,p),this.lastPage);
            this.query.page = p;
        }
    }
}
</script>
