<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Data Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Data Management
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="space-x-2 border-b border-gray-200 bg-white p-6"
                    >
                        <PrimaryButton class="bg-green-500 hover:bg-green-600"
                            ><a href="/persons/add/5"> Add 5 Persons </a>
                        </PrimaryButton>
                        <PrimaryButton class="bg-red-500 hover:bg-red-600"
                            ><a href="/persons/delete/5"> Remove 5 Persons </a>
                        </PrimaryButton>
                        <PrimaryButton
                            ><a href="/persons/delete/all"> Remove All</a>
                        </PrimaryButton>
                    </div>
                    <!-- <highcharts-demo></highcharts-demo> -->
                    <pre class="p-6">Total Persons: {{ data.length }}</pre>
                    <div
                        v-for="(person, index) in data"
                        :key="index"
                        class="p-6 text-gray-900"
                    >
                        <pre>{{ person.name }}</pre>
                        <pre>{{ person.gender }}</pre>
                        <pre>{{ person.email }}</pre>
                        <pre>{{ person.dob }}</pre>
                        <pre>{{ person.phone }}</pre>
                        <pre>{{ person.country }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts">
export default {
    props: {
        data: {
            type: Object,
            required: true,
        },
    },
};
</script>
