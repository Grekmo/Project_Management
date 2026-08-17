<template>
    <div class="main-layout">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">
                    <i class="bi bi-list-task me-2 text-primary"></i>
                    Tasks
                </h2>

                <RouterLink :to="{name:'manager.create.tasks'}" class="btn btn-success btn-lg shadow-sm">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Add Task
                </RouterLink>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" v-for="task in tasks" :key="task.id">
                    <div class="card shadow-lg border-0 rounded-4 h-100"
                            :class="{
                                'border-top border-4 border-warning': task.status === 'pending',
                                'border-top border-4 border-primary': task.status === 'in_progress',
                                'border-top border-4 border-success': task.status === 'completed'
                            }"
                        >
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-list-task text-primary me-2"></i>
                                    {{ task.name }}
                                </h5>
                                
                                <span
                                    class="badge px-3 py-2"
                                    :class="{
                                        'bg-warning text-dark': task.status === 'pending',
                                        'bg-primary': task.status === 'in_progress',
                                        'bg-success': task.status === 'completed'
                                    }"
                                >
                                    {{ status.find(statusItem => statusItem.value === task.status)?.label }}
                                </span>
                            </div>
                            <hr>
                            <p class="text-muted">
                                {{ task.description }}
                            </p>
                            <div class="mb-2">
                                <i class="bi bi-folder-fill text-warning me-2"></i>
                                {{ task.project?.name }}
                            </div>

                            <div class="mb-3">
                                <i class="bi bi-person-fill text-success me-2"></i>
                                {{ task.employee?.name }}
                            </div>

                            <div class="mb-3">
                                <i class="bi bi-clock-history text-danger me-2"></i>
                                {{ formDate(task.end_date) }}
                            </div>

                            <div class="card-footer bg-white border-0 pt-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <RouterLink
                                        class="btn btn-outline-primary rounded-circle"
                                        :to="{name:'manager.show.tasks',params:{id:task.id}}"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </RouterLink>
                                    <RouterLink
                                        class="btn btn-outline-warning rounded-circle"
                                        :to="{name:'manager.edit.tasks',params:{id:task.id}}"
                                    >
                                        <i class="bi bi-pencil-fill"></i>
                                    </RouterLink>
                                    <button
                                        class="btn btn-outline-danger rounded-circle"
                                        @click="deleteTask(task.id)"
                                    >
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import api from '@/services/axios';
    import { useToast } from "vue-toastification";
    const toast = useToast();

    export default {
        data() {
            return {
                tasks: {},
                status:[
                    {value: 'pending', label: 'Pending'},
                    {value: 'in_progress', label: 'In Progress'},
                    {value: 'completed', label: 'Completed'}
                ],
            }
        },

        mounted() {
            this.getTasks();
        },

        methods: {

            formDate(date){
                return date.split('T')[0];
            },

            getTasks () {
                api.get('/manager/tasks')
                .then((res) => {
                    console.log(res.data),
                    this.tasks = res.data.managerTasks;
                })
                .catch((error) => {
                    if (error.response.data.status === 404) {
                        this.error
                    }
                })
            },

            deleteTask (taskID) {
                if (!confirm('Are you sure you want to delete this task ? ')){
                    return
                }
                api.delete(`/manager/tasks/${taskID}`)
                .then((res) => {
                    toast.success(res.data.message);
                    this.getTasks();
                })
                .catch(error => {
                    if (error.response.data.status === 404) {
                        console.log(error.response.data.message);
                        toast.error('An error occurred while deleting the task.');
                    }
                })
            }

        }
    }
</script>