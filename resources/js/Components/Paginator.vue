<template>
    <Button class="m-2" @click="first">First</Button>
    <Button class="m-2" @click="previous">Previous</Button>
    <Button class="m-2" @click="next">Next</Button>
    <Button class="m-2" @click="last">Last</Button>
</template>

<script>
    import Button from "@/Components/Button";
    export default {
        name: "Paginator",
        components: {Button},
        props:{
          paginatordata:Object,
        },
        emits:[
            'first',
            'previous',
            'next',
            'last',
        ],
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
