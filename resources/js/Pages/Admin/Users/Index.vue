<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['users']);

const editingUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    is_admin: false,
});

const edit = (user) => {
    editingUser.value = user;
    form.name = user.name;
    form.email = user.email;
    form.is_admin = user.is_admin;
};

const update = () => {
    form.put(route('admin.users.update', editingUser.value.id), {
        onSuccess: () => {
            editingUser.value = null;
            form.reset();
        },
    });
};

const destroy = (user) => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        router.delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <Head title="Admin - Users" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 class="text-2xl font-bold mb-6">Manage Users</h1>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in users" :key="user.id">
                            <td class="px-6 py-4">
                                <div v-if="editingUser?.id === user.id">
                                    <input 
                                        type="text" 
                                        v-model="form.name"
                                        class="rounded-md border-gray-300"
                                    >
                                </div>
                                <div v-else>{{ user.name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="editingUser?.id === user.id">
                                    <input 
                                        type="email" 
                                        v-model="form.email"
                                        class="rounded-md border-gray-300"
                                    >
                                </div>
                                <div v-else>{{ user.email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="editingUser?.id === user.id">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.is_admin"
                                        class="rounded border-gray-300"
                                    >
                                </div>
                                <div v-else>{{ user.is_admin ? 'Yes' : 'No' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                            <td class="px-6 py-4">
                                <div v-if="editingUser?.id === user.id">
                                    <button @click="update" class="text-green-600 hover:text-green-900 mr-3">Save</button>
                                    <button @click="editingUser = null" class="text-gray-600 hover:text-gray-900">Cancel</button>
                                </div>
                                <div v-else>
                                    <button @click="edit(user)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button 
                                        @click="destroy(user)" 
                                        class="text-red-600 hover:text-red-900"
                                        :disabled="user.id === $page.props.auth.user.id"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 