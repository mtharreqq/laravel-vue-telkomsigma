<script setup>
import TableManagement from '@/Components/TableManagement.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    persons: {
        type: Array,
        default: () => [],
    },
    males: {
        type: Array,
        default: () => [],
    },
    females: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({});

const deletePerson = (id, name) => {
    // Optional: Show a confirmation dialog
    if (!confirm(`Are you sure you want to delete ${name}?`)) {
        return;
    }
    form.delete(`persons/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Handle success, e.g., show a notification
            console.log('Person deleted successfully!');
            window.location.reload();
            // Inertia.visit(window.location.href, { replace: true });
        },
    });
};
</script>

<template>
    <Head title="Manage Persons" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Manage Persons
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <TableManagement
                            :persons="persons"
                            :males="males"
                            :females="females"
                            :delete-person="deletePerson"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
