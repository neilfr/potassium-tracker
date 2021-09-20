<template>
    <div>
        <date-text :foo="logentry.ConsumedAt"/>
        <string-text :value="logentry.FoodDescription"/>
        <string-text :value="logentry.MeasureDescription"/>
        <number-text :value="logentry.K"/>
        <number-text :value="logentry.KCAL"/>
    </div>
</template>

<script>
    import DateText from '@/Components/DateText';
    import StringText from "@/Components/StringText";
    import NumberText from "@/Components/NumberText";
    export default {
        name: "LogEntry",
        components: {
            NumberText,
            StringText,
            DateText,
        },
        props:{
            logentry:Object,
        }
    }
</script>

<style scoped>

</style>
