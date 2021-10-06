<template>
    <div class="grid grid-cols-8 grid-rows-2 p-2">
        <div class="border col-span-1 row-span-2">
            favourite here
        </div>
        <div class="border col-span-6 row-span-2">
            <div class="border">
                <string-text class="col-span-6" :value="conversionfactor.FoodDescription"/>
            </div>
            <div class="border grid grid-cols-6">
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
        </div>
        <div class="border col-span-1 row-span-2">
            <Button @click="log">Log</Button>
        </div>
    </div>
</template>

<script>
    import StringText from "@/Components/StringText";
    import NumberText from "@/Components/NumberText";
    import Button from "@/Components/Button";
    export default {
        components: {
            StringText,
            NumberText,
            Button
        },
        name: "Conversionfactor",
        props:{
            conversionfactor:Object,
        },
        methods:{
            log(){
                let url = route('logentries.store');
                this.$inertia.visit(url, {
                    method: 'post',
                    data:{
                        'id':this.conversionfactor.id
                    },
                });
            }
        }
    }
</script>

<style scoped>

</style>
