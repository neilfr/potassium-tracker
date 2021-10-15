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
                        @search="handleSearch"
                        @favourite="handleFavouriteFilter"
                    />
                    <Button class="mt-2 ml-2" @click="addConversionFactor">Add Food</Button>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <conversionfactor-card
                            class="bg-gray-100 rounded-lg mb-2"
                            v-for="conversionfactor in conversionfactors.data"
                            :key="conversionfactor.id"
                            :conversionfactor="conversionfactor"
                            @toggle-favourite="handleToggleFavourite"
                        />
                    </div>
                    <paginator
                        @first="first"
                        @previous="previous"
                        @next="next"
                        @last="last"
                        :paginatordata="conversionfactors.meta"
                    />
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import ConversionfactorHeader from "@/Components/ConversionfactorHeader";
    import ConversionfactorCard from "@/Components/ConversionfactorCard";
    import Paginator from "@/Components/Paginator";
    import Button from "@/Components/Button";

    export default {
        components: {
            Paginator,
            BreezeAuthenticatedLayout,
            ConversionfactorCard,
            ConversionfactorHeader,
            Button,
        },
        props: {
            conversionfactors: Object,
        },
        data(){
            return {
                page: Number,
                searchText: String,
                favourite: Boolean,
            }
        },
        mounted() {
            this.page = this.conversionfactors.meta.current_page;
            this.searchText = '';
            this.favourite = false;
        },
        methods: {
            first() {
                this.page = 1;
                this.refreshPage();
            },
            previous() {
                if(this.page >1){
                    this.page--;
                }
                this.refreshPage();
            },
            next() {
                if (this.page < this.conversionfactors.meta.last_page) {
                    this.page++;
                }
                this.refreshPage();
            },
            last() {
                this.page = this.conversionfactors.meta.last_page;
                this.refreshPage();
            },
            refreshPage() {
                let url = route('conversionfactors.index');
                url += `?searchText=${this.searchText}`;
                url += `&favourite=${this.favourite}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':this.page,
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            handleSearch(searchText) {
                this.searchText=searchText;
                this.first();
            },
            handleFavouriteFilter(favourite){
                this.favourite=favourite;
                this.first();
            },
            addConversionFactor(){
                console.log('add conversion factor');
                let url = route('conversionfactors.create');
                this.$inertia.visit(url, {
                    data:{},
                });
            },
            handleToggleFavourite($conversionfactor){
                let url = route('conversionfactors.toggle-favourite', $conversionfactor);
                this.$inertia.visit(url,
                    {
                        method: 'post',
                        data:{},
                        preserveState: true,
                        preserveScroll: true,
                    });
            }
        }
    }
</script>
