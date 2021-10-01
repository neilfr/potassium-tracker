<template>
    <h1>{{formatteddatestring}}</h1>
</template>

<script>
    export default {
        name: "DateText",
        props:{
            datestring: String
        },
        data() {
            return {
               formatteddatestring: String
            }
        },
        mounted() {
            this.formatteddatestring = new Date(this.datestring).toDateString();
        }
    }
</script>

<style scoped>

</style>
