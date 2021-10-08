<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span v-if="nutrienttotals.length === 0">No Nutrient Totals</span>
        <span>
            <div class="mb-4">Nutrient Totals for Date Range</div>
            <div>
                <span class="mr-6" v-for="nutrienttotal in nutrienttotals">
                    <span>
                        <span>{{nutrienttotal.NutrientSymbol}}: </span>
                        <span>{{Math.round(nutrienttotal.total)}}</span>
                        <span>{{nutrienttotal.NutrientUnit}}</span>
                    </span>
                </span>
            </div>
        </span>
        <span>
            <label for="from">From:</label>
            <input id="from" v-model="startdate" type="date" @change="handleDateRangeChange"/>
            <label for="to">To:</label>
            <input id="to" v-model="enddate" type="date" @change="handleDateRangeChange"/>
        </span>
    </div>
</template>

<script>
    export default {
        name: "LogEntryHeader",
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
        },
    }
</script>

<style scoped>

</style>
