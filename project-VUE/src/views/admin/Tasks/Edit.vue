<template>
    <div class="container py-5">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-dark text-white py-3">
                <h3 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Task
                </h3>
            </div>

            <div class="card-body p-4">
                <ul v-if="Object.keys(errorList).length > 0" class="mb-4">
                    <li v-for="(error,index) in errorList" :key="index" class="text-danger">
                        {{ error[0] }}
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-list-task me-2 text-primary"></i>
                            Task Name
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="task.name"
                        >
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-flag-fill me-2 text-warning"></i>
                            Status
                        </label>

                        <select class="form-select" v-model="task.status">
                            <option v-for="statusItem in status" :key="statusItem.value" :value="statusItem.value">
                                {{ statusItem.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-card-text me-2 text-success"></i>
                        Description
                    </label>
                    <textarea
                        rows="4"
                        class="form-control"
                        v-model="task.description"
                    ></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-folder-fill me-2 text-warning"></i>
                            Project
                        </label>
                        <select class="form-select" v-model="task.project_id">
                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                {{ project.name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person-fill me-2 text-info"></i>
                            Employee
                        </label>
                        <select class="form-select" v-model="task.assigned_to">
                            <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                {{ employee.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                            End Date
                        </label>
                        <input type="date" class="form-control" v-model="task.end_date">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <RouterLink :to="{ name: 'admin.tasks' }" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back
                    </RouterLink>
                    <button class="btn btn-warning px-5" @click="updateTask(taskID)">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Update Task
                    </button>
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
                errorList: {},
                error: '',
                projects: [],
                employees: [],
                taskID: '',
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
        mounted() {
            this.taskID = this.$route.params.id;
            this.getTask(this.taskID);
            this.getEmployees();
            this.getProjects();
        },

        methods: {

            updateTask(taskID) {
                api.put(`/tasks/${taskID}`, this.task)
                .then( res => {
                    console.log(res.data);
                    this.errorList = {};
                    toast.success(res.data.message);
                    this.$router.push({ name: 'admin.tasks' });
                })
                .catch( error => {
                    if (error.response && error.response.status === 422) {
                        this.errorList = error.response.data.errors;
                        toast.error('Please correct the errors in the form.');
                    }else{
                        toast.error(error.response.data.message);
                    }
                })
            },

            getTask(taskID) {
                api.get(`/tasks/${taskID}`)
                .then( res => {
                    console.log(res.data);
                    this.task = res.data.task;
                    this.task.end_date = this.task.end_date.split('T')[0];
                })
                .catch( error => {
                    console.log(error.response.data.message);
                })
            },

            getEmployees() {
                api.get('/employees-list')
                .then( res => {
                    console.log(res.data);
                    this.employees = res.data.employees;
                })
                .catch( error => {
                    console.log(error.response.data.message);
                })
            },

            getProjects(){
                api.get('/projects')
                .then( res => {
                    this.projects = res.data.projects;
                })
                .catch( error => {
                    console.log(error.response.data.message);
                })
            },
        }
    }
</script>
