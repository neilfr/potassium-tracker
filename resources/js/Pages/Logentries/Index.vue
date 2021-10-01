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

    export default {
        components: {
            Paginator,
            LogEntryHeader,
            LogEntryCard,
            BreezeAuthenticatedLayout,
        },
        props: {
            logentries: Object,
            nutrienttotals: Object,
        },
        mounted() {
            let d = new Date();
            this.startdate = d.toISOString().substring(0,10);
            this.enddate = d.toISOString().substring(0,10);
        },
        data(){
            return {
                page: {
                    type: Number,
                    default: 1
                },
                startdate: String,
                enddate: String,
            }
        },
        methods: {
            handleDatechange(dates) {
                this.startdate=dates.startdate;
                this.enddate=dates.enddate;
                let url = '/logentries';
                url += `?from=${this.startdate}`;
                url += `&to=${this.enddate}`;
                console.log('url', url);
                this.$inertia.visit(url, {
                    data:{
                    },
                    preserveState: true,
                });
            },
            first() {
                console.log('still first');
            },
            previous() {
                console.log('still previous');
            },
            next() {
                console.log('still next');
                if (this.page < 4) {
                    this.page++;
                }
                let url = '/logentries';
                url += `?from=${dates.from}`;
                url += `&to=${dates.to}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':page
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            last() {
                console.log('still last');
            }
        }
    }
</script>
