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
        <select>
            <option v-for="item in stuff" value="item.value" :selected="item.value === 2">{{item.text}}</option>
        </select>
        <div>

        <label for="arg">SORTORDER:</label>
        <input id="arg" type="text" :value="sortorder"/>
        <span>
            <label class="flex-none" for="sort">Sort:</label>
            <select id="sort" class="ml-2 rounded" @change="handleUpdateSortOrder">
                <option v-for="sortOption in sortOrderOptions" :value="sortOption.value" :selected="sortOption.value == sortorder">{{sortOption.description}}</option>
            </select>
        </span>
        <span class="ml-8 flex-none flex items-center justify-self-center">
            <Button @click="addFood">Add Food</Button>
        </span>
        </div>
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
            sortorder: String,
        },
        emits: [
            'search',
            'toggleFavouriteFilter',
            'addFoodFactor',
        ],
        mounted() {
            // const bar = this.sortOrderOptions.filter(
            //     (option) => {
            //         return option.value === this.sortOrder;
            //         }
            //     );
            // this.selectedSortOrder = bar[0].value;
            console.log('sortorder in header', this.sortorder);
            console.log('favouritefilter in header', this.favouritefilter);
            // console.log('bar', bar);

            if(this.favouritefilter==='yes'){
                this.updatedFavouriteFilter = true;
            } else {
                this.updatedFavouriteFilter = false;
            }
        },
        data(){
            return {
                bar: null,
                sortOrder: '',
                poo: 3,
                stuff: [{
                        value:1, text:'a'
                    },{
                        value:2, text:'b'
                    },{
                        value:3, text:'c'
                    }
                ],
                searchText: '',
                updatedFavouriteFilter: Boolean,
                sortOrderOptions:[
                    {
                        value: "density-des",
                        description: "KCal / K(9..1)"
                    },
                    {
                        value:"density-asc",
                        description:"KCal/K (1..9)"
                    },
                    {
                        value:"food-description-asc",
                        description:"Food (a..z)"
                    },
                    {
                        value:"food-description-des",
                        description:"Food (z..a)"
                    }
                ],
                selectedSortOrder: Object,
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
            handleUpdateSortOrder(e){
                console.log('handleupdatesortorder, e.target.value', e.target.value)
                this.$emit('updateSort', e.target.value);
                // console.log('update sort');
            }
        }
    }
</script>
