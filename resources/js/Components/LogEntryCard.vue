<template>
    <div class="flex px-4 py-2">
        <div class="flex-none col-span-2 row-span-2 flex items-center justify-self-center">
            <input class="rounded" id="consumedAt" v-model="consumedAtDate" type="date" @change="handleUpdate"/>
        </div>
        <div class="flex-grow px-6">
            <div class="flex justify-between">
                <span>{{logentry.FoodDescription}} - {{logentry.MeasureDescription}}</span>
                <span>
                    <label for="portion">Portion (%): </label>
                    <input
                        id="portion"
                        type="number"
                        min="0"
                        v-model="portion"
                        @change="handleUpdate"
                    />
                </span>
            </div>
            <div class="flex justify-between">
                <span>KCal: {{Math.round(logentry.KCalValue)}}kcal</span>
                <span>K: {{Math.round(logentry.PotassiumValue)}}mg</span>
                <span>{{logentry.FoodGroupName}}</span>
            </div>
        </div>
        <div class="flex-none flex items-center justify-self-center col-span-1 row-span-2">
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
