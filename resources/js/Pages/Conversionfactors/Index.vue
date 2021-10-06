<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Foods
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <conversionfactor-header

                    />
                    <div class="p-6 bg-white border-b border-gray-200">
                        <conversionfactor-card class="bg-gray-100 rounded-lg mb-2" v-for="conversionfactor in conversionfactors.data" :key="conversionfactor.id" :conversionfactor="conversionfactor"/>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import ConversionfactorHeader from "@/Components/ConversionfactorHeader";
    import ConversionfactorCard from "@/Components/ConversionfactorCard";

    export default {
        components: {
            BreezeAuthenticatedLayout,
            ConversionfactorCard,
            ConversionfactorHeader,
        },
        props: {
            conversionfactors: Object,
        },
    }
</script>
