<template>
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
        <span class="" v-for="nutrienttotal in nutrienttotals">
            <span>{{nutrienttotal.NutrientSymbol}}: </span>
            <span>{{Math.round(nutrienttotal.total)}}</span>
            <span>{{nutrienttotal.NutrientUnit}}</span>
        </span>
        <span>
            <label for="from">From:</label>
            <input id="from" v-model="from" type="date" @change="handleDateRangeChange"/>
            <label for="to">From:</label>
            <input id="to" v-model="to" type="date" @change="handleDateRangeChange"/>
        </span>
    </div>
</template>

<script>
    export default {
        name: "LogEntryHeader",
        props: {
            nutrienttotals: Object,
        },
        data () {
            return {
                from: {
                    type: String,
                    default: null,
                },
                to: {
                    type: String,
                    default: null,
                }
            }
        },
        mounted() {
            let d = new Date();
            this.from = d.toISOString().substring(0,10);
            this.to = d.toISOString().substring(0,10);
        },
        methods: {
            handleDateRangeChange() {
                console.log('date change');
                let url = '/logentries';
                url += `?from=${this.from}`;
                url += `&to=${this.to}`;
                console.log('url', url);
                // this.$inertia.get(url, {}, {preserveState:true});
                this.$inertia.visit(url, {
                    data:{},
                    preserveState: true,
                });
            },
        }
    }
</script>

<style scoped>

</style>
