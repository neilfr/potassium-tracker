<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span class="" v-for="nutrienttotal in nutrienttotals">
            <span>{{nutrienttotal.NutrientSymbol}}: </span>
            <span>{{Math.round(nutrienttotal.total)}}</span>
            <span>{{nutrienttotal.NutrientUnit}}</span>
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
