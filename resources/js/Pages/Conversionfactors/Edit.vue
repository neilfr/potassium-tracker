<template>
    <breeze-authenticated-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg align-middle">
                    <div class="text-red-500" v-if="errors.logExists">{{errors.logExists}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <span class="w-1/6">
                            <label for="foodgroups">Foodgroup:</label>
                        </span>
                        <span class="w-5/6">
                            <select id="foodgroups">
                                <option v-for="foodgroup in foodgroups.data" value="FoodGroupID">{{foodgroup.FoodGroupName}}</option>
                            </select>
                        </span>
                    </div>
                    <div class="text-red-500" v-if="errors.foodDescription">{{errors.foodDescription}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="fooddescription">Description:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="fooddescription" v-model="foodDescription">
                        </div>
                    </div>
                    <div class="text-red-500" v-if="errors.measureDescription">{{errors.measureDescription}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="measuredescription">Serving Size:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="measuredescription" v-model="measureDescription">
                        </div>
                    </div>
                    <div class="text-red-500" v-if="nutrientsError">Both K and KCAL must be zero or greater</div>
                    <div v-for="nutrient in nutrients" class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label >{{nutrient.NutrientSymbol}}:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="number" v-model="nutrient.NutrientAmount">
                        </div>
                    </div>
                    <div class="m-2">
                        <Button class="m-2" @click="handleSave">Save</Button>
                        <Button class="m-2" @click="handleCancel">Cancel</Button>
                        <Button class="m-2" @click="handleDelete">Delete</Button>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import Button from "@/Components/Button";

    export default {
        components: {
            BreezeAuthenticatedLayout,
            Button,
        },
        props: {
            foodgroups:Object,
            conversionfactor:Object,
            errors:Object,
        },
        data(){
            return {
                foodDescription: String,
                foodGroupId: Number,
                measureDescription: String,
                nutrients: Array,
            }
        },
        mounted() {
            this.foodDescription=this.conversionfactor.data.FoodDescription;
            this.foodGroupId=this.conversionfactor.data.FoodGroupID;
            this.measureDescription=this.conversionfactor.data.MeasureDescription;
            this.nutrients=this.conversionfactor.data.nutrients;
        },
        computed: {
          nutrientsError: function () {
              if(
                  this.errors['nutrients.0.NutrientAmount'] ||
                  this.errors['nutrients.1.NutrientAmount']
              ) {
                  return true;
              }
              return false;
          }
        },
        methods: {
            handleCancel(){
                let url = route('conversionfactors.index');
                this.$inertia.visit(url, {});
            },
            handleSave(){
                let url = route('conversionfactors.update', this.conversionfactor.data.id);
                this.$inertia.visit(url, {
                    method: 'patch',
                    data: {
                        'foodDescription': this.foodDescription,
                        'foodGroupId': this.foodGroupId,
                        'measureDescription': this.measureDescription,
                        'nutrients': this.nutrients,
                    }
                });
            },
            handleDelete(){
                let url = route('conversionfactors.destroy', this.conversionfactor.data.id);
                this.$inertia.visit(url, {
                    method: 'delete',
                    data: {}
                });
            }
        }
    }
</script>
