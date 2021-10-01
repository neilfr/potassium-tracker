<template>
    <div class="grid grid-cols-7 grid-rows-2 p-2">
        <date-text class="col-span-1 row-span-2" :datestring="logentry.ConsumedAt"/>
        <string-text class="col-span-6" :value="logentry.FoodDescription"/>
        <string-text class="col-span-2" :value="logentry.MeasureDescription"/>
        <span class="col-span-2" v-for="nutrient in logentry.nutrients">
            <span>{{nutrient.NutrientSymbol}}:</span>
            <string-text :value="Math.round(nutrient.NutrientAmount)"/>
            <span>{{nutrient.NutrientUnit}}</span>
        </span>
    </div>
</template>

<script>
    import DateText from '@/Components/DateText';
    import StringText from "@/Components/StringText";
    import NumberText from "@/Components/NumberText";
    export default {
        name: "LogEntry",
        components: {
            NumberText,
            StringText,
            DateText,
        },
        props:{
            logentry:Object,
        }
    }
</script>

<style scoped>

</style>
