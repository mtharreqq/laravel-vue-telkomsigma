<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import toTitleCase from '@/lib/titleCase';
import Dashboard from '@/Pages/Dashboard/Dashboard.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import '../../../css/style.css';

const props = defineProps({
    persons: Array,
    males: Array,
    females: Array,
    fromDate: String,
    toDate: String,
});

// Create reactive data
const startDate = ref(props.fromDate);
const endDate = ref(props.toDate);

// Methods
const customSearch = () => {
    const route = useRoute();
    route.push({
        name: 'dashboard',
        query: {
            startDate: startDate.value,
            endDate: endDate.value,
        },
    });
};

// Returns an object where each key is a year, and the value is an object with the count of males and females born in that year.
const totalBirth = {};
props.persons.forEach((person) => {
    const year = new Date(person.dob).getFullYear();
    if (!totalBirth[year]) {
        totalBirth[year] = { male: 0, female: 0 };
    }
    totalBirth[year][person.gender]++;
});

const data = [
    ['Name', 'Gender', 'Date of Birth'],
    ...props.persons.map(({ name, gender, dob }) => [
        name,
        toTitleCase(gender),
        dob,
    ]),
];

const config = {
    dataPool: {
        connectors: [
            {
                type: 'JSON',
                id: 'synchro-data',
                options: {
                    data,
                },
            },
        ],
    },
    editMode: {
        enabled: false,
        contextMenu: {
            enabled: true,
            items: ['editMode'],
        },
    },
    gui: {
        layouts: [
            {
                rows: [
                    {
                        id: 'row-1',
                        cells: [
                            {
                                id: 'dashboard-row-1-cell-1',
                            },
                        ],
                    },
                    {
                        id: 'row-2',
                        cells: [
                            {
                                id: 'dashboard-row-2-cell-1',
                            },
                            {
                                id: 'dashboard-row-2-cell-2',
                            },
                        ],
                    },
                    {
                        id: 'row-3',
                        cells: [
                            {
                                id: 'dashboard-row-3-cell-1',
                            },
                            {
                                id: 'dashboard-row-3-cell-2',
                            },
                        ],
                    },
                    {
                        id: 'row-4',
                        cells: [
                            {
                                id: 'dashboard-row-4-cell-1',
                            },
                        ],
                    },
                ],
            },
        ],
    },
    components: [
        // TOTAL PERSONS
        {
            type: 'KPI',
            cell: 'dashboard-row-1-cell-1',
            value: props.persons.length,
            valueFormat: '{value}',
            title: 'Total Persons',
        },
        // MALE
        {
            type: 'KPI',
            cell: 'dashboard-row-2-cell-1',
            value: props.males.length,
            title: 'Males',
            valueFormat: '{value}',
        },
        // FEMALE
        {
            type: 'KPI',
            cell: 'dashboard-row-2-cell-2',
            value: props.females.length,
            title: 'Females',
            valueFormat: '{value}',
        },
        // PIE GENDER
        {
            type: 'Highcharts',
            cell: 'dashboard-row-3-cell-1',
            renderTo: 'dashboard-row-3-cell-1',
            chartOptions: {
                styledMode: true,
                title: {
                    text: 'Comparison of Males and Females',
                },
                series: [
                    {
                        name: 'Total',
                        type: 'pie',
                        data: [
                            {
                                name: 'Males',
                                y: props.males.length,
                            },
                            {
                                name: 'Females',
                                y: props.females.length,
                            },
                        ],
                    },
                ],
            },
        },
        // PIE COUNTRY (BELUM JADI)
        // COLUMN CHART
        {
            type: 'Highcharts',
            renderTo: 'dashboard-row-4-cell-1',
            chartOptions: {
                title: {
                    text: 'Total Births Each Year',
                },
                rangeSelector: {
                    enabled: true,
                    verticalAlign: 'top',
                    x: 0,
                    y: 0,
                },
                xAxis: {
                    categories: Object.keys(totalBirth),
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count births',
                    },
                    stackLabels: {
                        enabled: true,
                    },
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                    },
                },
                chart: {
                    type: 'column',
                },
                series: [
                    {
                        name: 'Males',
                        data: Object.values(totalBirth).map(
                            (yearData) => yearData.male,
                        ),
                    },
                    {
                        name: 'Females',
                        data: Object.values(totalBirth).map(
                            (yearData) => yearData.female,
                        ),
                    },
                ],
            },
        },
        // TABLE
        {
            renderTo: 'dashboard-row-3-cell-2',
            cell: 'dashboard-row-3-cell-2',
            connector: {
                id: 'synchro-data',
            },
            type: 'DataGrid',
            sync: {
                highlight: true,
            },
            dataGridOptions: {
                credits: {
                    enabled: false,
                },
                columns: [
                    {
                        id: 'Vitamin A',
                        cells: {
                            editable: true,
                        },
                    },
                ],
            },
        },
    ],
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div id="app" class="highcharts-light py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div class="text-gray-900">
                        <div class="overflow-hidden bg-white p-6 sm:rounded-lg">
                            <div
                                class="block bg-white pb-5 text-left text-lg font-semibold text-gray-900 dark:bg-gray-800 dark:text-white rtl:text-right"
                            >
                                Demographic Overview
                                <p
                                    class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400"
                                >
                                    A Detailed Analysis of Gender Ratios and
                                    Yearly Birth Trends. Filter birth dates to
                                    analyze demographics across all views.
                                </p>
                            </div>
                            <form method="GET" action="/dashboard" class="">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Start Date Field -->
                                    <div>
                                        <InputLabel
                                            for="startDate"
                                            value="Start Date:"
                                        />
                                        <TextInput
                                            id="startDate"
                                            type="date"
                                            class="block w-full"
                                            v-model="startDate"
                                            name="startDate"
                                        />
                                    </div>

                                    <!-- End Date Field -->
                                    <div>
                                        <InputLabel
                                            for="endDate"
                                            value="End Date:"
                                        />
                                        <TextInput
                                            id="endDate"
                                            type="date"
                                            class="block w-full"
                                            v-model="endDate"
                                            name="endDate"
                                        />
                                    </div>

                                    <!-- Filter Button -->
                                    <div class="">
                                        <PrimaryButton
                                            class="bg-blue-600 hover:bg-blue-700 active:bg-blue-600"
                                            @click="customSearch"
                                        >
                                            Filter Date
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <Dashboard :config="config"></Dashboard>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
