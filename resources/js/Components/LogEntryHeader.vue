<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <span class="flex justify-between">
            <span>
                <label for="from">From:</label>
                <input class="ml-2" id="from" v-model="startdate" type="date" @change="handleDateRangeChange"/>
                <label class="ml-2" for="to">To:</label>
                <input class="ml-2" id="to" v-model="enddate" type="date" @change="handleDateRangeChange"/>
            </span>
            <div class="flex items-center">
                <span v-if="nutrienttotals.length === 0">No Nutrient Totals</span>
                <span v-if="nutrienttotals.length > 0" class="mr-6" v-for="nutrienttotal in nutrienttotals">
                    <span>
                        <span>{{nutrienttotal.NutrientSymbol}}: </span>
                        <span>{{Math.round(nutrienttotal.total)}}</span>
                        <span>{{nutrienttotal.NutrientUnit}}</span>
                    </span>
                </span>
            </div>
            <div class="flex items-center justify-self-center">
                <Button class="mt-2 ml-2" @click="addLogentry">Add Logentry</Button>
            </div>
        </span>
    </div>
</template>

<script>
    import Button from "@/Components/Button";

    export default {
        name: "LogEntryHeader",
        components: {
          Button,
        },
        props: {
            nutrienttotals: Object,
            startdate: String,
            enddate: String
        },
        emits:[
            'datechange',
        ],
        methods: {
            handleDateRangeChange() {
                this.$emit('datechange', {
                    startdate: this.startdate,
                    enddate: this.enddate,
                })
            },
            addLogentry() {
                this.$emit('addLogentry');
            }
        },
    }
</script>

<style scoped>

</style>
