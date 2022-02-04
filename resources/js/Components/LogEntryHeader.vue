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
                <span class="mr-6">KCal: {{Math.round(kcalTotal)}}kcal</span>
                <span class="mr-6">K: {{Math.round(potassiumTotal)}}mg</span>
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
            kcalTotal: Number,
            potassiumTotal: Number,
            startdate: String,
            enddate: String
        },
        emits:[
            'datechange',
        ],
        methods: {
            handleDateRangeChange() {
                console.log('logentry header startdate', this.startdate);
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
