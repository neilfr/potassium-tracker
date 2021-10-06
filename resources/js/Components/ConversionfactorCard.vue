<template>
    <div class="grid grid-cols-7 grid-rows-2 p-2">
        <div class="col-span-1 row-span-2">
            favourite here
        </div>
        <string-text class="col-span-6" :value="conversionfactor.FoodDescription"/>
        <span class="col-span-2">
            <span>Quantity: </span>
            <string-text :value="conversionfactor.MeasureDescription"/>
        </span>
        <span class="col-span-2" v-for="nutrient in conversionfactor.nutrients">
            <span>{{nutrient.NutrientSymbol}}: </span>
            <string-text :value="Math.round(nutrient.NutrientAmount)"/>
            <span>{{nutrient.NutrientUnit}}</span>
        </span>
    </div>
</template>

<script>
    import StringText from "@/Components/StringText";
    import NumberText from "@/Components/NumberText";
    export default {
        components: {
          StringText,
          NumberText,
        },
        name: "Conversionfactor",
        props:{
            conversionfactor:Object,
        }
    }
</script>

<style scoped>

</style>
