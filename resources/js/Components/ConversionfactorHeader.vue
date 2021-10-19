<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span>
            <label for="search">Search:</label>
            <input class="ml-2 rounded" type="text" id="search" @input="updateSearchText" v-model="searchText"/>
        </span>
        <span>
            <label for="favourite">Favourite:</label>
            <input type="checkbox" id="favourite" @change="toggleFavourite" :checked="favouriteFilter"/>
        </span>
    </div>
</template>

<script>
    export default {
        name: "ConversionfactorHeader",
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
            }
        }
    }
</script>
