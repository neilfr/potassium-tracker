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
                            <select id="foodgroups" @change="handleFoodgroupChange($event)">
                                <option v-for="foodgroup in foodgroups.data" :value="foodgroup.FoodGroupID">{{foodgroup.FoodGroupName}}</option>
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
                    <div class="text-red-500" v-if="errors.measureDescription">The quantity is a required field</div>
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
                            <input type="number" id="kCalValue" v-model="kCalValue" min="0">
                        </div>
                    </div>
                    <div class="text-red-500" v-if="errors.potassiumValue">{{errors.potassiumValue}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="potassiumValue">K:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="number" id="potassiumValue" v-model="potassiumValue" min="0">
                        </div>
                    </div>
                    <div class="m-2">
                        <Button class="m-2" @click="handleSave">Save</Button>
                        <Button class="m-2" @click="handleCancel">Cancel</Button>
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
            errors:Object,
            foodgroups:Object,
        },
        data(){
            return {
                foodDescription: String,
                foodGroupId: Number,
                measureDescription: String,
                kCalValue: Number,
                potassiumValue: Number,
            }
        },
        mounted() {
            this.foodDescription='';
            this.foodGroupId=1;
            this.measureDescription='';
            this.kCalValue=0;
            this.potassiumValue=0;
        },
        methods: {
            handleCancel(){
                let url = route('foods.index');
                this.$inertia.visit(url, {});
            },
            handleFoodgroupChange(e){
              this.foodGroupId = e.target.value;
            },
            handleSave(){
                let url = route('foods.store');
                this.$inertia.visit(url, {
                    method: 'post',
                    data: {
                        'foodDescription': this.foodDescription,
                        'foodGroupId': this.foodGroupId,
                        'measureDescription': this.measureDescription,
                        'potassiumValue': this.potassiumValue,
                        'kCalValue': this.kCalValue,
                    }
                });
            }
        }
    }
</script>
