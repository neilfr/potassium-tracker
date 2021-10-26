<template>
    <div class="flex px-4 py-2">
        <div class="flex-none col-span-1 row-span-2 flex">
            <input class="self-center" type="checkbox" :checked="favourite" @change="emitToggleFavourite">
        </div>
        <div class="flex-grow px-6">
            <div class="flex justify-between">
                <span>
                    {{conversionfactor.FoodDescription}} - {{conversionfactor.MeasureDescription}}
                </span>
            </div>
            <div class="flex justify-between">
                <span v-for="nutrient in conversionfactor.nutrients">
                    <span>{{nutrient.NutrientSymbol}}: </span>
                    <string-text :value="Math.round(nutrient.NutrientAmount)"/>
                    <span>{{nutrient.NutrientUnit}}</span>
                </span>
                <span>{{conversionfactor.FoodGroupName}}</span>
            </div>
        </div>
        <div class="flex-none col-span-2 row-span-2 flex items-center justify-self-center">
            <Button class="ml-2 self-center" @click="log">Log</Button>
            <Button
                v-if="conversionfactor.editable"
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
        name: "Conversionfactor",
        props:{
            conversionfactor:Object,
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
          this.favourite = this.conversionfactor.Favourite;
        },
        methods:{
            emitToggleFavourite(){
                this.favourite = !this.favourite;
                this.$emit('toggle-favourite', {
                    'conversionfactor': this.conversionfactor
                });
            },
            log(){
                let url = route('logentries.store');
                this.$inertia.visit(url, {
                    method: 'post',
                    data:{
                        'id':this.conversionfactor.id
                    },
                });
            },
            edit(){
                this.$emit('edit', {
                    'conversionfactor': this.conversionfactor
                });
            }

        }
    }
</script>

<style scoped>

</style>
