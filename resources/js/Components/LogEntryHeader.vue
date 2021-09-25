<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span class="" v-for="nutrienttotal, key in nutrienttotals">
            <span>{{key}}:</span>
            <span>{{nutrienttotal}}</span>
        </span>
    </div>
</template>

<script>
    export default {
        name: "LogEntryHeader",
        props: {
            nutrienttotals: Object,
        }
    }
</script>

<style scoped>

</style>
