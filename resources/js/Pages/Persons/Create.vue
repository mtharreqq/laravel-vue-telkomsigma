<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    gender: '',
    phone: '',
    dob: '',
    country: '',
});

const submit = () => {
    form.post('/persons');
};
</script>

<template>
    <Head title="Manage Persons" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Post
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form
                            @submit.prevent="submit"
                            class="grid grid-cols-1 gap-6 lg:grid-cols-2"
                        >
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

                            <div>
                                <InputLabel for="email" value="Email" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>

                            <div>
                                <InputLabel for="gender" value="Gender" />

                                <TextInput
                                    id="gender"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.gender"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.gender"
                                />
                            </div>

                            <div>
                                <InputLabel for="phone" value="Phone" />

                                <TextInput
                                    id="phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.phone"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.phone"
                                />
                            </div>

                            <div>
                                <InputLabel for="dob" value="Date of Birth" />

                                <TextInput
                                    id="dob"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.dob"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.dob"
                                />
                            </div>

                            <div>
                                <InputLabel for="country" value="Country" />

                                <TextInput
                                    id="country"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.country"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.country"
                                />
                            </div>

                            <div
                                class="col-span-1 mt-4 flex items-center justify-end lg:col-span-2"
                            >
                                <PrimaryButton
                                    class="ms-4"
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
