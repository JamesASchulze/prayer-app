<script setup>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import PrayerButton from '@/Components/PrayerButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps(['request']);
const showUpdateForm = ref(false);
const editingUpdate = ref(null);

const form = useForm({
    update: '',
});

const editForm = useForm({
    update: '',
});

const submitUpdate = () => {
    form.post(route('requests.update.store', props.request.id), {
        onSuccess: () => {
            form.reset();
            showUpdateForm.value = false;
        },
    });
};

const startEditing = (update) => {
    editingUpdate.value = update;
    editForm.update = update.update;
};

const cancelEditing = () => {
    editingUpdate.value = null;
    editForm.reset();
};

const submitEdit = () => {
    editForm.put(route('requests.updates.update', editingUpdate.value.id), {
        onSuccess: () => {
            editingUpdate.value = null;
            editForm.reset();
        },
    });
};

const deleteUpdate = (updateId) => {
    if (confirm('Are you sure you want to delete this update?')) {
        useForm().delete(route('requests.updates.destroy', updateId));
    }
};
</script>

<template>
    <div class="p-6 flex space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <div class="flex-1">
            <h3 class="text-lg font-semibold">{{ request?.title }}</h3>
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ request?.user?.name }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ dayjs(request?.created_at).fromNow() }}</small>
                </div>
            </div>
            <p class="mt-4 text-lg text-gray-900">{{ request?.request }}</p>
            
            <!-- Updates Section -->
            <div v-if="request.updates?.length" class="mt-6 space-y-4">
                <h4 class="font-medium text-gray-900">Updates:</h4>
                <div v-for="update in request.updates" :key="update.id" 
                     class="bg-gray-50 rounded-lg p-4">
                    <!-- Edit Form -->
                    <div v-if="editingUpdate?.id === update.id">
                        <form @submit.prevent="submitEdit">
                            <textarea
                                v-model="editForm.update"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                rows="3"
                            ></textarea>
                            <div class="mt-2 flex justify-end space-x-2">
                                <button
                                    type="button"
                                    @click="cancelEditing"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                                    :disabled="editForm.processing"
                                >
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Update Display -->
                    <div v-else>
                        <p class="text-gray-600">{{ update.update }}</p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                Updated by {{ update.user?.name }} 
                                {{ dayjs(update.created_at).fromNow() }}
                            </span>
                            <!-- Edit/Delete buttons (only show for update owner) -->
                            <div v-if="update.user_id === $page.props.auth.user?.id" 
                                 class="flex space-x-2">
                                <button 
                                    @click="startEditing(update)"
                                    class="text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="deleteUpdate(update.id)"
                                    class="text-sm text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Update Button (only show for request owner) -->
            <div v-if="request.user_id === $page.props.auth.user?.id" class="mt-4">
                <button 
                    @click="showUpdateForm = !showUpdateForm"
                    class="text-sm text-indigo-600 hover:text-indigo-900"
                >
                    {{ showUpdateForm ? 'Cancel Update' : 'Add Update' }}
                </button>

                <!-- Update Form -->
                <form v-if="showUpdateForm" @submit.prevent="submitUpdate" class="mt-4">
                    <textarea
                        v-model="form.update"
                        placeholder="Share an update about this prayer request..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        rows="3"
                    ></textarea>
                    <div class="mt-2 flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                            :disabled="form.processing"
                        >
                            Post Update
                        </button>
                    </div>
                </form>
            </div>

            <div v-if="request?.notes" class="mt-4 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700">Additional Notes:</h4>
                <p class="mt-1 text-gray-600">{{ request?.notes }}</p>
            </div>

            <p class="mt-4 text-lg text-gray-900">Praise?: {{ request?.is_praise ? '✔️' : '❌' }}</p>
            
            <div class="mt-4">
                <PrayerButton 
                    :request-id="request.id"
                    :initial-prayer-count="request.prayer_count"
                    :is-praise="Boolean(request?.is_praise)"
                />
            </div>
        </div>
    </div>
</template>