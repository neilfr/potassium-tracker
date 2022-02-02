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
                    <span>{{food.KCalSymbol}}: </span>
                    <span>{{food.KCalValue}}</span>
                    <span>{{food.KCalUnit}}</span>
                </span>
                <span>
                    <span>{{food.PotassiumSymbol}}: </span>
                    <span>{{food.PotassiumValue}}</span>
                    <span>{{food.PotassiumUnit}}</span>
                </span>
                <span>
                    <span>{{Number.parseFloat(food.NutrientDensity).toFixed(3)}}&nbsp</span>
                    <span>{{food.KCalUnit}}/{{food.PotassiumUnit}}</span>
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
                // let url = route('logentries.store');
                // this.$inertia.visit(url, {
                //     method: 'post',
                //     data:{
                //         'id':this.food.FoodID
                //     },
                // });
            },
            edit(){
                console.log('edit')
                // this.$emit('edit', {
                //     'food': this.food
                // });
            }

        }
    }
</script>

<style scoped>

</style>
