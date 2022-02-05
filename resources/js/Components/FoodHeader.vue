<template>
    <div class="p-6 bg-white border-b border-gray-200 flex">
        <span class="flex-none flex items-center justify-self-center">
            <label for="favourite">Favourites Filter:</label>
            <input class="ml-2" type="checkbox" id="favourite" @change="toggleFavouriteFilter" :checked="updatedFavouriteFilter"/>
        </span>
        <span class="flex-grow flex items-center justify-self-center justify-between px-8">
            <label class="flex-none" for="search">Search:</label>
            <input class="flex-grow ml-2 rounded" type="text" id="search" @input="updateSearchText" v-model="searchText"/>
        </span>
        <span>
            <label class="flex-none" for="sort">Sort:</label>
            <select id="sort" class="ml-2 rounded" @click="updateSort">
                <option value="density-des">KCal/K (9..1)</option>
                <option value="density-asc">KCal/K (1..9)</option>
                <option value="food-description-asc">Food (a..z)</option>
                <option value="food-description-des">Food (z..a)</option>
            </select>
        </span>
        <span class="ml-8 flex-none flex items-center justify-self-center">
            <Button @click="addFood">Add Food</Button>
        </span>
    </div>
</template>

<script>
    import Button from "@/Components/Button";

    export default {
        name: "FoodHeader",
        components: {
            Button
        },
        props:{
            favouritefilter: String,
        },
        emits: [
            'search',
            'toggleFavouriteFilter',
            'addFoodFactor',
        ],
        mounted() {
            if(this.favouritefilter==='yes'){
                this.updatedFavouriteFilter = true;
            } else {
                this.updatedFavouriteFilter = false;
            }
        },
        data(){
            return {
                searchText: '',
                updatedFavouriteFilter: Boolean,
            }
        },
        methods:{
            updateSearchText(){
                this.$emit('search', this.searchText);
            },
            toggleFavouriteFilter(e){
                this.$emit('toggleFavouriteFilter', e.target.checked);
            },
            addFood(){
                this.$emit('addFood');
            },
            updateSort(){
                console.log('update sort');
            }
        }
    }
</script>
