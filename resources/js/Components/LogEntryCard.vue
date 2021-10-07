<template>
    <div class="grid grid-cols-9 grid-rows-2 gap-2 p-2">
        <div class="col-span-2 row-span-2">
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
            console.log('dates', this.logentry.ConsumedAt.substring(0,10));
            // let d = new Date();
            this.consumedAtDate = this.logentry.ConsumedAt.substring(0,10);
        },
        methods:{
            destroy(){
                let url = route('logentries.destroy', this.logentry.id);
                this.$inertia.delete(url, {});
            },
            handleDateChange(){
                console.log('update the date to', this.consumedAtDate);
                let url = route('logentries.update', this.logentry.id);
                this.$inertia.visit(url, {
                    method: 'patch',
                    data:{
                        'ConsumedAt':this.consumedAtDate
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }
    }
</script>

<style scoped>

</style>
