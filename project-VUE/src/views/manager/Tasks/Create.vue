<template>
    <div class="main-layout">
        <div class="container py-4">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark text-white py-3">
                    <h3 class="mb-0">
                        <i class="bi bi-list-task me-2"></i>
                        Create New Task
                    </h3>
                </div>
                <div class="card-body">
                    <ul v-if="Object.keys(errorList).length > 0">
                        <li v-for="(error, index) in errorList" :key="index" class="text-danger">
                            {{ error[0] }}
                        </li>
                    </ul>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text text-primary me-2"></i>
                                Task Name
                            </label>
                            <input
                                type="text"
                                class="form-control form-control-lg"
                                v-model="task.name"
                                placeholder="Enter task name"
                            >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-kanban-fill text-warning me-2"></i>
                                Project
                            </label>
                            <select class="form-select form-select-lg" v-model="task.project_id">
                                <option value="">Select Project</option>
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-workspace text-success me-2"></i>
                                Assigned To Employee
                            </label>
                            <select class="form-select form-select-lg" v-model="task.assigned_to">
                                <option value="">Select Employee</option>
                                <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                    {{ employee.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-flag-fill text-danger me-2"></i>
                                Status
                            </label>
                            <select class="form-select form-select-lg" v-model="task.status">
                                <option value="">Select Status</option>
                                <option v-for="statu in status" :key="statu.value" :value="statu.value">
                                    {{ statu.label }}
                                </option>
                                
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text-fill text-info me-2"></i>
                                Description
                            </label>
                            <textarea
                                rows="5"
                                class="form-control"
                                v-model="task.description"
                                placeholder="Task description..."
                            ></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event-fill text-danger me-2"></i>
                                End Date
                            </label>
                            <input
                                type="date"
                                class="form-control form-control-lg"
                                v-model="task.end_date"
                            >
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-flex justify-content-end gap-3">
                        <RouterLink
                            :to="{name:'manager.tasks'}"
                            class="btn btn-outline-secondary px-4"
                        >
                            <i class="bi bi-arrow-left-circle me-2"></i>
                            Cancel
                        </RouterLink>
                        <button @click="saveTask()" type="submit" class="btn btn-success px-5">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Create Task
                        </button>
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
                errorList: {},
                error: '',
                projects: [],
                employees: [],
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
            this.getEmployees();
            this.getProjects();
        },
        methods: {

            getEmployees() {
                api.get('/manager/team')
                .then( res => {
                    console.log(res.data);
                    this.employees = res.data.users;
                })
                .catch( error => {
                    console.log(error.response.data.errors);
                })
            },

            getProjects() {
                api.get('/manager/my-projects')
                .then( res => {
                    console.log(res.data);
                    this.projects = res.data.projects;
                })
                .catch( error => {
                    console.log(error.response.data.errors);
                })
            },

            saveTask(){
                api.post('/manager/tasks', this.task)
                .then( res => {
                    console.log(res.data);
                    this.errorList = {};
                    toast.success(res.data.message);
                    this.$router.push( {name: 'manager.tasks'} );
                })
                .catch(error => {
                    console.log(error.response);
                    this.errorList = error.response?.data?.errors || {
                        general: [error.response?.data?.message || 'An error occurred while saving the task.']
                    };
                })
            }
        }        
    }

</script>