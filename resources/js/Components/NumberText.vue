<template>
    <input type="number" min=0 :value="updatedValue">
</template>

<script>
    export default {
        name: "NumberText",
        props:{
            value:Number,
        },
        data(){
            return {
                updatedValue: Number,
            }
        },
        mounted(){
            this.updatedValue = Math.round(this.value);
        }
    }
</script>

<style scoped>

</style>
