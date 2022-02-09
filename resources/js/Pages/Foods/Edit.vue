<template>
    <breeze-authenticated-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg align-middle">

                    <div class="flex items-center mt-2 ml-2">
                        <span class="w-1/6">
                            <label for="foodgroups">Foodgroup:</label>
                        </span>
                        <span class="w-5/6">
                            <select id="foodgroups" v-model="selectedFoodGroup">
                                <option v-for="foodgroup in foodgroups.data" :value="foodgroup">{{foodgroup.FoodGroupName}}</option>
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

                    <div class="text-red-500" v-if="errors.kCalValue">{{errors.kCalValue}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="kCalValue">KCal:</label>
                        </div>
                        <div class="w-5/6">
                            <span>
                                <input type="number" id="kCalValue" v-model="kCalValue" min="0">
                                <span> kcal</span>
                            </span>
                        </div>
                    </div>

                    <div class="text-red-500" v-if="errors.potassiumValue">{{errors.potassiumValue}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="potassiumValue">K:</label>
                        </div>
                        <div class="w-5/6">
                            <span>
                                <input type="number" id="potassiumValue" v-model="potassiumValue" min="0">
                                <span> mg</span>
                            </span>
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
            food:Object,
            errors:Object,
        },
        data(){
            return {
                newFoodId: Number,
                foodDescription: String,
                foodGroupId: Number,
                foodGroupName: String,
                measureDescription: String,
                kCalValue: Number,
                potassiumValue: Number,
                nutrientDensity: Number,
                selectedFoodGroup: Object,
            }
        },
        mounted() {
            this.newFoodId=this.food.data.NewfoodID;
            this.foodDescription=this.food.data.FoodDescription;
            this.measureDescription=this.food.data.MeasureDescription;
            this.kCalValue=this.food.data.KCalValue;
            this.potassiumValue=this.food.data.PotassiumValue;
            this.nutrientDensity=this.food.data.NutrientDensity;
            this.selectedFoodGroup=this.foodgroups.data.filter((foodgroup)=> foodgroup.FoodGroupID === this.food.data.FoodGroupID)[0];
        },
        methods: {
            handleCancel(){
                let url = route('foods.index');
                this.$inertia.visit(url, {});
            },
            handleSave(){
                let url = route('foods.update', this.food.data.NewfoodID);
                this.$inertia.visit(url, {
                    method: 'patch',
                    data: {
                        'newFoodId': this.newFoodId,
                        'foodDescription': this.foodDescription,
                        'foodGroupId': this.selectedFoodGroup.FoodGroupID,
                        'measureDescription': this.measureDescription,
                        'kCalValue': this.kCalValue,
                        'potassiumValue': this.potassiumValue,
                    }
                });
            },
            handleDelete(){
                let url = route('foods.destroy', this.food.data.NewfoodID);
                this.$inertia.visit(url, {
                    method: 'delete',
                    data: {}
                });
            }
        }
    }
</script>
