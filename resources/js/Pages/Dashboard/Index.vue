<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Dashboard from '@/Pages/Dashboard/Dashboard.vue';
import { Head } from '@inertiajs/vue3';
import '../../../css/style.css';

const props = defineProps({
    persons: Array,
    males: Array,
    females: Array,
    dob: Array,
});

// Returns an object where each key is a year, and the value is an object with the count of males and females born in that year.
const totalBirth = {};
props.persons.forEach((person) => {
    const year = new Date(person.dob).getFullYear();
    if (!totalBirth[year]) {
        totalBirth[year] = { male: 0, female: 0 };
    }
    totalBirth[year][person.gender]++;
});

const config = {
    dataPool: {
        connectors: [
            {
                id: 'micro-element',
                type: 'JSON',
                options: {
                    firstRowAsNames: false,
                    columnNames: ['Food', 'Vitamin A', 'Iron'],
                    data: [
                        ['Beef Liver', 6421, 6.5],
                        ['Lamb Liver', 2122, 6.5],
                        ['Cod Liver Oil', 1350, 0.9],
                        ['Mackerel', 388, 1],
                        ['Tuna', 214, 0.6],
                    ],
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
                        cells: [
                            {
                                id: 'kpi-wrapper',
                                layout: {
                                    rows: [
                                        {
                                            cells: [
                                                {
                                                    id: 'total-persons',
                                                },
                                            ],
                                        },
                                        {
                                            cells: [
                                                {
                                                    id: 'male',
                                                },
                                                {
                                                    id: 'female',
                                                },
                                            ],
                                        },
                                    ],
                                },
                            },
                            // {
                            //     id: 'pie-chart',
                            // },
                        ],
                    },
                    {
                        cells: [
                            {
                                id: 'pie-gender',
                            },
                            // {
                            //     id: 'pie-country',
                            // },
                        ],
                    },
                    {
                        cells: [
                            // {
                            //     id: 'table',
                            // },
                            {
                                id: 'column-chart',
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
            cell: 'total-persons',
            value: props.persons.length,
            valueFormat: '{value}',
            title: 'Total Persons',
        },
        // MALE
        {
            type: 'KPI',
            cell: 'male',
            value: props.males.length,
            title: 'Males',
            valueFormat: '{value}',
        },
        // FEMALE
        {
            type: 'KPI',
            cell: 'female',
            value: props.females.length,
            title: 'Females',
            valueFormat: '{value}',
        },
        // PIE GENDER
        {
            type: 'Highcharts',
            renderTo: 'pie-gender',
            chartOptions: {
                title: {
                    text: 'Comparison of Males and Females',
                },
                colors: ['#FCE700', '#F8C4B4'],
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
            renderTo: 'column-chart',

            chartOptions: {
                title: {
                    text: 'Total Births Each Year',
                },
                xAxis: {
                    categories: Object.keys(totalBirth),
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count trophies',
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
                series: [
                    {
                        name: 'Males',
                        type: 'column',
                        data: Object.values(totalBirth).map(
                            (yearData) => yearData.male,
                        ),
                    },
                    {
                        name: 'Females',
                        type: 'column',

                        data: Object.values(totalBirth).map(
                            (yearData) => yearData.female,
                        ),
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
        <div id="app" class="highcharts-light">
            <Dashboard :config="config"></Dashboard>
        </div>
    </AuthenticatedLayout>
</template>
