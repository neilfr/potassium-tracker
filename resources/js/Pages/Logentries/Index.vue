<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Log Entries
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <log-entry-header
                        :nutrienttotals="nutrienttotals.data"
                        :startdate="startdate"
                        :enddate="enddate"
                        @datechange="handleDatechange"
                    />
                    <Button class="mt-2 ml-2" @click="addLogentry">Add Logentry</Button>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <log-entry-card class="bg-gray-100 rounded-lg mb-2" v-for="logentry in logentries.data" :key="logentry.id" :logentry="logentry"/>
                    </div>
                    <paginator
                        @first="first"
                        @previous="previous"
                        @next="next"
                        @last="last"
                        :paginatordata="logentries.meta"
                    />
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import LogEntryCard from "@/Components/LogEntryCard";
    import LogEntryHeader from "@/Components/LogEntryHeader";
    import Paginator from "@/Components/Paginator";
    import Button from "@/Components/Button";

    export default {
        components: {
            Button,
            Paginator,
            LogEntryHeader,
            LogEntryCard,
            BreezeAuthenticatedLayout,
        },
        props: {
            logentries: Object,
            nutrienttotals: Object,
        },
        data(){
            return {
                page: Number,
                startdate: String,
                enddate: String,
            }
        },
        mounted() {
            let d = new Date();
            this.startdate = d.toISOString().substring(0,10);
            this.enddate = d.toISOString().substring(0,10);
            this.page = this.logentries.meta.current_page;
        },
        methods: {
            addLogentry() {
                let url = route('conversionfactors.index');
                this.$inertia.visit(url, {
                    data:{
                        'page':this.page
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            handleDatechange(dates) {
                this.startdate=dates.startdate;
                this.enddate=dates.enddate;
                this.refreshPage();
            },
            first() {
                this.page = 1;
                this.refreshPage();
            },
            previous() {
                if(this.page >1){
                    this.page--;
                    this.refreshPage();
                }
            },
            next() {
                if (this.page < this.logentries.meta.last_page) {
                    this.page++;
                    this.refreshPage();
                }
            },
            last() {
                this.page = this.logentries.meta.last_page;
                this.refreshPage();
            },
            refreshPage() {
                let url = route('logentries.index');
                url += `?from=${this.startdate}`;
                url += `&to=${this.enddate}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':this.page
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }
    }
</script>
