<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span>
            <label for="search">Search:</label>
            <input class="ml-2 rounded" type="text" id="search" @input="updateSearchText" v-model="searchText"/>
        </span>
        <span>
            <label for="favourite">Favourite:</label>
            <input type="checkbox" id="favourite" @change="updateFavourite" v-model="favourite"/>
        </span>
    </div>
</template>

<script>
    export default {
        name: "ConversionfactorHeader",
        props: {},
        emits: [
            'search',
            'favourite',
        ],
        data(){
            return {
                searchText: '',
                favourite: true,
            }
        },
        methods:{
            updateSearchText(){
                this.$emit('search', this.searchText);
            },
            updateFavourite(){
                this.$emit('favourite', this.favourite)
            }
        }
    }
</script>
