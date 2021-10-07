<template>
    <div class="grid grid-cols-8 grid-rows-2 p-2">
        <div class="col-span-1 row-span-2">
            <input id="consumedAt" v-model="consumedAtDate" type="date" @change="handleDateChange"/>
        </div>
        <div class="border col-span-6 row-span-2">
            <string-text class="col-span-6" :value="logentry.FoodDescription"/>
            <div class="border grid grid-cols-6">
                <span class="col-span-2">
                    <span>Quantity: </span>
                    <string-text :value="logentry.MeasureDescription"/>
                </span>
                <span class="col-span-2" v-for="nutrient in logentry.nutrients">
                    <span>{{nutrient.NutrientSymbol}}: </span>
                    <string-text :value="Math.round(nutrient.NutrientAmount)"/>
                    <span>{{nutrient.NutrientUnit}}</span>
                </span>
            </div>
        </div>
        <div class="border col-span-1 row-span-2">
            <Button @click="destroy">Delete</Button>
        </div>
    </div>
</template>

<script>
    import DateText from '@/Components/DateText';
    import StringText from "@/Components/StringText";
    import NumberText from "@/Components/NumberText";
    import Button from "@/Components/Button";

    export default {
        name: "LogEntry",
        components: {
            NumberText,
            StringText,
            DateText,
            Button,
        },
        props:{
            logentry:Object,
        },
        data() {
            return {
                consumedAtDate: String,
            }
        },
        mounted(){
            let d = new Date();
            this.consumedAtDate = d.toISOString().substring(0,10);
        },
        methods:{
            destroy(){
                let url = route('logentries.destroy', this.logentry.id);
                this.$inertia.delete(url, {});
            },
            handleDateChange(){
                console.log('update the date to', this.consumedAtDate);
            }
        }
    }
</script>

<style scoped>

</style>
