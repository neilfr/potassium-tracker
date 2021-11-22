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
                    <div class="text-red-500" v-if="errors.measureDescription">The quantity is a required field</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="measuredescription">Serving Size:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="measuredescription" v-model="measureDescription">
                        </div>
                    </div>
                    <div class="text-red-500" v-if="errors.kcal">{{errors.kcal}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="kcal">KCal:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="kcal" v-model="kcal">
                        </div>
                    </div>
                    <div class="text-red-500" v-if="errors.k">{{errors.k}}</div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="k">K:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="k" v-model="k">
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
                kcal: Number,
                k: Number,
            }
        },
        mounted() {
            this.foodDescription='';
            this.foodGroupId=1;
            this.measureDescription='';
            this.kcal=0;
            this.k=0;
            console.log('errors', this.errors.foodDescription);
        },
        methods: {
            handleCancel(){
                let url = route('conversionfactors.index');
                this.$inertia.visit(url, {});
            },
            handleSave(){
                let url = route('conversionfactors.store');
                this.$inertia.visit(url, {
                    method: 'post',
                    data: {
                        'foodDescription': this.foodDescription,
                        'foodGroupId': this.foodGroupId,
                        'measureDescription': this.measureDescription,
                        'k': this.k,
                        'kcal': this.kcal,
                    }
                });
            }
        }
    }
</script>
