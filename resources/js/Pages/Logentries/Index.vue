<template>
    <div>
        <h1>Hello!</h1>
        <table>
            <tr v-for="logentry in logentries.data" :key="logentry.id">
                <td>{{logentry.FoodDescription}}</td>
                <td>{{logentry.MeasureDescription}}</td>
                <td>{{logentry.POTASSIUM}}</td>
                <td>{{logentry.['ENERGY (KILOCALORIES)']}}</td>

            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        name: "Index.vue",
        props: {
            'logentries': Object,
        },
        mounted () {
          console.log('hi');
          console.log(this.logentries);
        }
    }

</script>

<style scoped>

</style>
