<template>
    <breeze-authenticated-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <food-header
                            @search="handleSearch"
                            @toggleFavouriteFilter="handleFavouriteFilter"
                            @addFood="addFood"
                            @updateSort="updateSortOrder"
                            :favouritefilter="favouritefilter"
                        />
                        <food-card
                            class="bg-gray-100 rounded-lg mb-2"
                            v-for="food in foods.data"
                            :key="food.NewfoodID"
                            :food="food"
                            @toggle-favourite="handleToggleFavourite"
                            @edit="handleEditFood"
                        />
                        <paginator
                            @first="first"
                            @previous="previous"
                            @next="next"
                            @last="last"
                            :paginatordata="foods.meta"
                        />
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import FoodCard from '@/Components/FoodCard'
    import Paginator from '@/Components/Paginator'
    import FoodHeader from "@/Components/FoodHeader";
    export default {
        name: "FoodsIndex",
        components: {
            BreezeAuthenticatedLayout,
            FoodCard,
            Paginator,
            FoodHeader
        },
        computed: {
            paginatorData: function () {
                return{
                    current_page: this.foods.meta.current_page,
                    last_page: this.foods.meta.last_page,
                }
            }
        },
        props: {
            foods: Object,
            favouritefilter: String,
        },
        emits: [
            'toggleFavouriteFilter',
        ],
        data(){
            return {
                searchText: String,
                updatedFavouriteFilter: '',
                sortOrder: String,
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
                if(this.foods.meta.current_page >1){
                    this.goToPage(this.foods.meta.current_page - 1);
                }
            },
            next() {
                if (this.foods.meta.current_page < this.foods.meta.last_page) {
                    this.goToPage(this.foods.meta.current_page + 1);
                }
            },
            last() {
                this.goToPage(this.foods.meta.last_page);
            },
            goToPage(page) {
                let url = route('foods.index');
                url += `?searchText=${this.searchText}`;
                url += `&favouritefilter=${this.updatedFavouriteFilter}`;
                url += `&sortOrder=${this.sortOrder}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':page,
                    },
                    preserveState: true,
                    preserveScroll: true,
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
            addFood(){
                let url = route('foods.create');
                this.$inertia.visit(url, {
                    data:{},
                });
            },
            handleToggleFavourite(food){
                let url = route('foods.toggle-favourite', food.NewfoodID);
                this.$inertia.visit(url,
                    {
                        method: 'post',
                        data:{},
                        preserveState: true,
                        preserveScroll: true,
                    });
            },
            handleEditFood(food){
                let url = route('foods.edit', food.NewfoodID);
                this.$inertia.visit(url,
                    {
                        method: 'get',
                        data:{},
                        preserveState: true,
                        preserveScroll: true,
                    });
            },
            updateSortOrder(sortOrder){
                console.log('sortOrder',sortOrder);
                this.sortOrder=sortOrder;
                this.first();
            }
        }
    }
</script>

<style scoped>

</style>
