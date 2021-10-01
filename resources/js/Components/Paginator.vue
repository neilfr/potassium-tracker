<template>
    <Button class="m-2" @click="first">First</Button>
    <Button class="m-2" @click="previous">Previous</Button>
    <Button class="m-2" @click="next">Next</Button>
    <Button class="m-2" @click="last">Last</Button>
    <span>Page {{paginatordata.current_page}} of {{paginatordata.last_page}}</span>
</template>

<script>
    import Button from "@/Components/Button";
    export default {
        name: "Paginator",
        components: {Button},
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
