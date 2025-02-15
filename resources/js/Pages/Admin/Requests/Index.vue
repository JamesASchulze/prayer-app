<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['requests']);

const editingRequest = ref(null);

const form = useForm({
    request: '',
    is_praise: false,
    follow_up_email: '',
});

const edit = (request) => {
    editingRequest.value = request;
    form.request = request.request;
    form.is_praise = request.is_praise;
    form.follow_up_email = request.follow_up_email;
};

const update = () => {
    form.put(route('admin.requests.update', editingRequest.value.id), {
        onSuccess: () => {
            editingRequest.value = null;
            form.reset();
        },
    });
};

const destroy = (request) => {
    if (confirm('Are you sure you want to delete this request?')) {
        router.delete(route('admin.requests.destroy', request.id));
    }
};
</script>

<template>
    <Head title="Admin - Prayer Requests" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 class="text-2xl font-bold mb-6">Manage Prayer Requests</h1>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="request in requests" :key="request.id">
                            <td class="px-6 py-4">
                                <div v-if="editingRequest?.id === request.id">
                                    <textarea
                                        v-model="form.request"
                                        class="w-full rounded-md border-gray-300"
                                        rows="3"
                                    ></textarea>
                                </div>
                                <div v-else>{{ request.request }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="editingRequest?.id === request.id">
                                    <input type="checkbox" v-model="form.is_praise">
                                </div>
                                <div v-else>{{ request.is_praise ? 'Praise' : 'Request' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="editingRequest?.id === request.id">
                                    <input type="email" v-model="form.follow_up_email" class="rounded-md border-gray-300">
                                </div>
                                <div v-else>{{ request.follow_up_email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ new Date(request.created_at).toLocaleDateString() }}</td>
                            <td class="px-6 py-4">
                                <div v-if="editingRequest?.id === request.id">
                                    <button @click="update" class="text-green-600 hover:text-green-900 mr-3">Save</button>
                                    <button @click="editingRequest = null" class="text-gray-600 hover:text-gray-900">Cancel</button>
                                </div>
                                <div v-else>
                                    <button @click="edit(request)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button @click="destroy(request)" class="text-red-600 hover:text-red-900">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 