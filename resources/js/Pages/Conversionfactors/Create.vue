<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Food
            </h2>
        </template>

        <div class="py-12">
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
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="fooddescription">Description:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="fooddescription">
                        </div>
                    </div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="kcal">KCal:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="kcal">
                        </div>
                    </div>
                    <div class="flex items-center mt-2 ml-2">
                        <div class="w-1/6">
                            <label for="k">K:</label>
                        </div>
                        <div class="w-5/6">
                            <input type="text" id="k">
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
            this.kcal=0;
            this.k=0;
        },
        methods: {
            handleCancel(){
                let url = route('conversionfactors.index');
                this.$inertia.visit(url, {});
            },
            handleSave(){
                console.log('save');
                // let url = route('conversionfactors.store');
                // this.$inertia.visit(url, {
                //     data: {
                //         'foodDescription': this.foodDescription,
                //         'foodGroupId': this.foodGroupId,
                //         'measureDescription': this.measureDescription,
                //         'k': this.k,
                //         'kcal': this.kcal,
                //     }
                // });
            }
        }
    }
</script>
