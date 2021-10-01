<template>
    <button @click="first">First</button>
    <button @click="previous">Previous</button>
    <button @click="next">Next</button>
    <button @click="last">Last</button>
    <span>{{paginatordata.current_page}} of {{paginatordata.last_page}}</span>
</template>

<script>
    export default {
        name: "Paginator",
        props:{
          paginatordata:Object,
        },
        methods:{
            first() {
                this.$emit('first');
            },
            previous() {
                this.$emit('previous');
            },
            next() {
                this.$emit('next');
            },
            last() {
                this.$emit('last');
            }
        }
    }
</script>

<style scoped>

</style>
