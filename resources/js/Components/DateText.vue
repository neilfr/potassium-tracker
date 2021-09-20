<template>
    <h1>{{bar}}</h1>
</template>

<script>
    export default {
        name: "DateText",
        props:{
            foo: String
        },
        data() {
            return {
               bar: String
            }
        },
        mounted() {
            this.bar = new Date(this.foo).toDateString();
        }
    }
</script>

<style scoped>

</style>
