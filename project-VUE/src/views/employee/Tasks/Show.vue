<template>
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header "
             :class="{
                'bg-warning text-dark': task.status === 'pending',
                'bg-primary text-white': task.status === 'in_progress',
                'bg-success text-white': task.status === 'completed'
            }">
                <h3>
                    <i class="bi bi-eye-fill me-2"></i>
                    Task Details
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Title</h6>
                        <h5>{{ task.name }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge fs-6"
                            :class="{
                                'bg-warning text-dark': task.status === 'pending',
                                'bg-primary': task.status === 'in_progress',
                                'bg-success': task.status === 'completed'
                            }
                            ">
                            {{ status.find(statuItem => statuItem.value === task.status)?.label }}
                        </span>
                    </div>
                </div>
                <hr>
                <h6>Description</h6>
                <p>
                    {{ task.description }}
                </p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6>
                            <i class="bi bi-folder-fill text-warning me-2"></i>
                            Project
                        </h6>
                        {{ task.project?.name }}
                    </div>
                    <div class="col-md-6">
                        <h6>
                            <i class="bi bi-person-fill text-success me-2"></i>
                            Assigned Employee
                        </h6>
                        {{ task.employee?.name }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6>
                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                            End Date
                        </h6>
                        {{ formDate(task.end_date) }}
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <RouterLink :to="{ name: 'employee.myTasks' }" class="btn btn-outline-secondary">
                        Back
                    </RouterLink>
                    <RouterLink :to="{ name: 'employee.edit.tasks', params: { id:task.id } }" class="btn btn-warning">
                        <i class="bi bi-pencil-fill me-2"></i>
                        Edit
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '@/services/axios';

    export default {
        data() {
            return {
               task: {
                    name: '',
                    description: '',
                    status: '',
                    end_date: '',
                    project_id: '',
                    assigned_to: '',
                },
                status: [
                    {value: 'pending', label: 'Pending'},
                    {value: 'in_progress', label: 'In Progress'},
                    {value: 'completed', label: 'Completed'},
                ],
            }
        },

        mounted(){
            this.taskID = this.$route.params.id;
            this.getTask(this.taskID);
        },

        methods: {

            formDate(date) {
                return date.split('T')[0];
            },

            getTask(taskID) {
                api.get(`/employee/show/tasks/${taskID}`)
                .then( res => {
                    console.log(res.data.task);
                    this.task = res.data.task;
                })
                .catch( error => {
                    console.log(error.response.data);
                })
            }
        },
        
    }
</script>