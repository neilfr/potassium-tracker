<template>
    <div>
        <h1>Hello!</h1>
        <table>
            <tr v-for="logentry in logentries">
                <td>{{logentry.UserID}}</td>
                <td>{{logentry.ConversionFactorID}}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        name: "Index.vue",
        props: {
            'logentries': Object,
        }
    }

</script>

<style scoped>

</style>
