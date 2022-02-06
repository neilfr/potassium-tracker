<template>
    <div class="flex px-4 py-2">
        <div class="flex-none col-span-1 row-span-2 flex">
            <input class="self-center" type="checkbox" :checked="favourite" @change="emitToggleFavourite">
        </div>
        <div class="flex-grow px-6">
            <div class="flex justify-between">
                <span>
                    {{food.FoodDescription}} - {{food.MeasureDescription}}
                </span>
            </div>
            <div class="grid grid-cols-4">
                <span>
                    <span>KCAL: </span>
                    <span>{{parseFloat(food.KCalValue).toFixed(1)}}</span>
                    <span> KCal</span>
                </span>
                <span>
                    <span>K: </span>
                    <span>{{parseFloat(food.PotassiumValue).toFixed(1)}}</span>
                    <span> mg</span>
                </span>
                <span>
                    <span>{{food.NutrientDensity === null ? 'NA' : parseFloat(food.NutrientDensity).toFixed(1)}}&nbsp</span>
                    <span> KCal / mgs</span>
                </span>
                <span>{{food.FoodGroupName}}</span>
            </div>
        </div>
        <div class="flex-none col-span-2 row-span-2 flex items-center justify-self-center">
            <Button class="ml-2 self-center" @click="log">Log</Button>
            <Button
                v-if="food.Editable"
                class="ml-2 self-center"
                @click="edit"
            >
                Edit
            </Button>
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
        name: "Food",
        props:{
            food:Object,
        },
        emits:[
            'toggle-favourite'
        ],
        data(){
            return{
              favourite: Boolean
            }
        },
        mounted() {
            this.favourite = this.food.Favourite;
        },
        methods:{
            emitToggleFavourite(){
                this.favourite = !this.favourite;
                this.$emit('toggle-favourite', this.food);
            },
            log(){
                console.log('log')
                let url = route('newlogentries.store');
                this.$inertia.visit(url, {
                    method: 'post',
                    data:{
                        'NewfoodID':this.food.NewfoodID
                    },
                });
            },
            edit(){
                this.$emit('edit', this.food);
            }

        }
    }
</script>

<style scoped>

</style>
