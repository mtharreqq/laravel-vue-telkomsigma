<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import countriesData from '@/lib/countries.json';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    person: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    name: props.person.name,
    gender: props.person.gender,
    dob: props.person.dob,
    country: props.person.country,
});

const submit = () => {
    form.put(`/persons/${props.person.id}`);
};
</script>

<template>
    <Head title="Manage Persons" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Person
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- NAME -->
                            <div>
                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <!-- GENDER -->
                                <div>
                                    <InputLabel for="gender" value="Gender" />
                                    <select
                                        name="gender"
                                        id="gender-select"
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.gender"
                                    >
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.gender"
                                    />
                                </div>
                                <!-- DOB -->
                                <div>
                                    <InputLabel
                                        for="dob"
                                        value="Date of Birth"
                                    />
                                    <TextInput
                                        id="dob"
                                        type="date"
                                        class="block w-full"
                                        v-model="form.dob"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.dob"
                                    />
                                </div>
                            </div>
                            <!-- COUNTRY -->
                            <div>
                                <InputLabel for="country" value="Country" />
                                <select
                                    name="country"
                                    id="country-select"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.country"
                                >
                                    <option
                                        v-for="(
                                            country, index
                                        ) in countriesData"
                                        :key="index"
                                        :value="country.name"
                                    >
                                        {{ country.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.country"
                                />
                            </div>
                            <!-- SUBMIT -->
                            <div
                                class="col-span-1 mt-4 flex items-center justify-end lg:col-span-2"
                            >
                                <PrimaryButton
                                    class="ms-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-600"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Add Person
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
