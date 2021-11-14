<template>
    <div class="p-6 bg-white border-b border-gray-200 flex">
        <span class="flex-none flex items-center justify-self-center">
            <label for="favourite">Favourites Filter:</label>
            <input class="ml-2" type="checkbox" id="favourite" @change="toggleFavouriteFilter" v-model="favouriteFilter"/>
        </span>
        <span class="flex-grow flex items-center justify-self-center justify-between px-8">
            <label class="flex-none" for="search">Search:</label>
            <input class="flex-grow ml-2 rounded" type="text" id="search" @input="updateSearchText" v-model="searchText"/>
        </span>
        <div class="flex-none flex items-center justify-self-center">
            <Button class="mt-2 ml-2" @click="addConversionFactor">Add Food</Button>
        </div>
    </div>
</template>

<script>
    import Button from "@/Components/Button";

    export default {
        name: "ConversionfactorHeader",
        components: {
            Button
        },
        emits: [
            'search',
            'toggledFavourite',
        ],
        data(){
            return {
                searchText: '',
                favouriteFilter: true,
            }
        },
        methods:{
            updateSearchText(){
                this.$emit('search', this.searchText);
            },
            toggleFavouriteFilter(){
                this.$emit('toggleFavouriteFilter', this.favouriteFilter);
            },
            addConversionFactor(){
                this.$emit('addConversionFactor');
            }
        }
    }
</script>
