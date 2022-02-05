<template>
    <div class="flex px-4 py-2">
        <input class="w-48 rounded" id="consumedAt" v-model="consumedAtDate" type="date" @change="handleUpdate"/>
        <div class="flex-grow ml-4">
            <div class="flex">
                <span class="flex-grow">{{logentry.FoodDescription}} - {{logentry.MeasureDescription}}</span>
            </div>
            <div class="text-sm grid grid-cols-11 justify-between">
                <span class="col-span-2">KCal: {{Math.round(logentry.KCalValue)}} kcal</span>
                <span class="col-span-2">K: {{Math.round(logentry.PotassiumValue)}} mg</span>
                <span class="col-span-2">{{logentry.NutrientDensity}} KCal/mg</span>
                <span class="ml-4 col-span-5">{{logentry.FoodGroupName}}</span>
            </div>
        </div>
        <div class="ml-8">
            <label for="portion">Portion(%): </label>
            <input class="w-20"
                   id="portion"
                   type="number"
                   min="0"
                   v-model="portion"
                   @change="handleUpdate"
            />
        </div>
        <div class="ml-4">
            <Button @click="destroy">Delete</Button>
        </div>
    </div>
</template>

<script>
    import DateText from '@/Components/DateText';
    import StringText from "@/Components/StringText";
    import NumberText from "@/Components/NumberText";
    import Button from "@/Components/Button";
    import Label from "@/Components/Label";

    export default {
        name: "LogEntry",
        components: {
            Label,
            NumberText,
            StringText,
            DateText,
            Button,
        },
        props:{
            logentry:Object,
        },
        emits:[
            'destroy',
            'updated',
        ],
        data() {
            return {
                consumedAtDate: String,
                portion: Number,
            }
        },
        mounted(){
            this.consumedAtDate = this.logentry.ConsumedAt.substring(0,10);
            this.portion = this.logentry.portion;
        },
        methods:{
            destroy(){
                this.$emit('destroy',{
                    'id': this.logentry.id
                });
            },
            handleUpdate(){
                this.$emit('updated',{
                    'id': this.logentry.id,
                    'ConsumedAt': this.consumedAtDate,
                    'portion': this.portion,
                });
            },
        }
    }
</script>

<style scoped>

</style>
