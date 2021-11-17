<template>
    <breeze-authenticated-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <conversionfactor-header
                        @search="handleSearch"
                        @toggleFavouriteFilter="handleFavouriteFilter"
                        @addConversionFactor="addConversionFactor"
                        :favouritefilter="favouritefilter"
                    />
                    <div class="p-6 bg-white border-b border-gray-200">
                        <conversionfactor-card
                            class="bg-gray-100 rounded-lg mb-2"
                            v-for="conversionfactor in conversionfactors.data"
                            :key="conversionfactor.id"
                            :conversionfactor="conversionfactor"
                            @toggle-favourite="handleToggleFavourite"
                            @edit="handleEditConversionfactor"
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
            favouritefilter: String,
        },
        emits: [
            'toggleFavouriteFilter',
        ],
        data(){
            return {
                searchText: String,
                updatedFavouriteFilter: '',
            }
        },
        mounted() {
            this.searchText = '';
            this.updatedFavouriteFilter = this.favouritefilter;
        },
        methods: {
            first() {
                this.goToPage(1);
            },
            previous() {
                if(this.conversionfactors.meta.current_page >1){
                    this.goToPage(this.conversionfactors.meta.current_page - 1);
                }
            },
            next() {
                if (this.conversionfactors.meta.current_page < this.conversionfactors.meta.last_page) {
                    this.goToPage(this.conversionfactors.meta.current_page + 1);
                }
            },
            last() {
                this.goToPage(this.conversionfactors.meta.last_page);
            },
            goToPage(page) {
                let url = route('conversionfactors.index');
                url += `?searchText=${this.searchText}`;
                url += `&favouritefilter=${this.updatedFavouriteFilter}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':page,
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            refresh() {
                let url = route('conversionfactors.index');
                url += `?searchText=${this.searchText}`;
                url += `&favouritefilter=${this.updatedFavouriteFilter}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':1,
                    }
                });
            },
            handleSearch(searchText) {
                this.searchText=searchText;
                this.first();
            },
            handleFavouriteFilter(newstate){
                if(newstate){
                    this.updatedFavouriteFilter = 'yes';
                }else{
                    this.updatedFavouriteFilter = 'no';
                }
                this.first();
            },
            addConversionFactor(){
                let url = route('conversionfactors.create');
                this.$inertia.visit(url, {
                    data:{},
                });
            },
            handleToggleFavourite(conversionfactor){
                let url = route('conversionfactors.toggle-favourite', conversionfactor);
                this.$inertia.visit(url,
                    {
                        method: 'post',
                        data:{},
                        preserveState: true,
                        preserveScroll: true,
                    });
            },
            handleEditConversionfactor(conversionfactor){
                let url = route('conversionfactors.edit', conversionfactor);
                this.$inertia.visit(url,
                    {
                        method: 'get',
                        data:{},
                        preserveState: true,
                        preserveScroll: true,
                    });
            }
        }
    }
</script>
