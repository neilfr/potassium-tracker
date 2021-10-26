<template>
    <div class="p-6 bg-white border-b border-gray-200 flex">
        <span class="flex-none flex items-center justify-self-center">
            <label for="favourite">Favourites Filter:</label>
            <input class="ml-2" type="checkbox" id="favourite" @change="toggleFavourite" :checked="favouriteFilter"/>
        </span>
        <span class="flex-grow mx-16">
            <label for="search">Search:</label>
            <input class="ml-2 rounded w-11/12" type="text" id="search" @input="updateSearchText" v-model="searchText"/>
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
        props: {
            favouriteFilter: Boolean,
        },
        emits: [
            'search',
            'toggledFavourite',
        ],
        data(){
            return {
                searchText: '',
            }
        },
        methods:{
            updateSearchText(){
                this.$emit('search', this.searchText);
            },
            toggleFavourite(){
                console.log('togglefavourite', this.favouriteState);
                this.$emit('toggledFavourite', this.favouriteState);
            },
            addConversionFactor(){
                this.$emit('addConversionFactor');
            }
        }
    }
</script>
