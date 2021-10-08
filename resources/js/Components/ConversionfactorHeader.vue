<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span>
            <label for="search">Search:</label>
            <input class="ml-2 rounded" type="text" id="search" @input="updateSearchText" v-model="searchText"/>
        </span>
    </div>
</template>

<script>
    export default {
        name: "ConversionfactorHeader",
        props: {},
        emits: [
            'search'
        ],
        data(){
            return {
                searchText: ''
            }
        },
        methods:{
            updateSearchText(){
                console.log('search text is:', this.searchText);
                this.$emit('search', this.searchText);
            }
        }
    }
</script>
