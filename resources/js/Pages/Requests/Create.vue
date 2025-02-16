<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    request: '',
    is_praise: false,
});
</script>

<template>
    <Head title="New Prayer Request" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Prayer Request
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="form.post(route('requests.store'), { onSuccess: () => form.reset() })">
                            <div class="mb-4">
                                <input
                                    v-model="form.title"
                                    type="text"
                                    placeholder="Title"
                                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <InputError :message="form.errors.title" class="mt-2" />
                            </div>

                            <textarea
                                v-model="form.request"
                                placeholder="How can we pray for you?"
                                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            ></textarea>
                            <InputError :message="form.errors.request" class="mt-2" />

                            <div class="mt-4">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        v-model="form.is_praise"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                    <span class="ml-2 text-gray-600">This is a praise report</span>
                                </label>
                                <InputError :message="form.errors.is_praise" class="mt-2" />
                            </div>

                            <!-- <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Additional Notes (optional)
                                </label>
                                <textarea
                                    v-model="form.notes"
                                    placeholder="Add any additional context or updates..."
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    rows="3"
                                ></textarea>
                                <InputError :message="form.errors.notes" class="mt-2" />
                            </div> -->

                            <div class="mt-4 flex justify-end">
                                <PrimaryButton>Submit</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 