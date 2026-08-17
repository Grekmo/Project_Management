<template>
    <div class="main-layout">
        <div class="container py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">
                    <i class="bi bi-kanban-fill text-primary me-2"></i>
                    Projects
                </h2>
            </div>
            <!-- Table -->
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>
                                        <i class="bi bi-folder-fill text-warning me-1"></i>
                                        Project
                                    </th>
                                    <th>
                                        <i class="bi bi-person-workspace text-primary me-1"></i>
                                        Manager
                                    </th>
                                    <th>
                                        <i class="bi bi-people-fill text-success me-1"></i>
                                        Employees
                                    </th>
                                    <th>
                                        <i class="bi bi-list-check text-info me-1"></i>
                                        Tasks
                                    </th>
                                    <th>
                                        <i class="bi bi-flag-fill text-danger me-1"></i>
                                        Status
                                    </th>
                                    <th class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="project in projects" :key="project.id">
                                    <td class="fw-bold">
                                        #{{ project.id }}
                                    </td>
                                    <td class="fw-semibold">
                                        {{ project.name }}
                                    </td>
                                    <td>
                                        {{ project.manager?.name }}
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-6 text-success">
                                            {{ project.employees.length }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ project.tasks.length }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            v-if="project.status === 'to_do'"
                                            class="badge bg-warning text-dark px-3 py-2"
                                        >
                                            To Do
                                        </span>
                                        <span
                                            v-else-if="project.status === 'in_progress'"
                                            class="badge bg-primary  px-3 py-2"
                                        >
                                            In Progress
                                        </span>
                                        <span
                                            v-else
                                            class="badge bg-success px-3 py-2"
                                        >
                                            Completed
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <RouterLink
                                                :to="{name:'manager.show.projects',params:{id:project.id}}"
                                                class="btn btn-info btn-sm"
                                            >
                                                <i class="bi bi-eye-fill"></i>
                                            </RouterLink>
                                            <RouterLink
                                                :to="{name:'manager.edit.projects',params:{id:project.id}}"
                                                class="btn btn-warning btn-sm"
                                            >
                                                <i class="bi bi-pencil-fill"></i>
                                            </RouterLink>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                projects: {},
                status:[
                    {value: 'to_do', label: 'To Do'},
                    {value: 'in_progress', label: 'In Progress'},
                    {value: 'completed', label: 'Completed'}
                ],
            }
        },

        mounted() {
            this.getProjects();
            console.log(JSON.parse(localStorage.getItem('user')));
        },

        methods: {

            formDate(date){
                return date.split('T')[0];
            },

            getProjects () {
                api.get('/manager/my-projects')
                .then((res) => {
                    console.log(res.data),
                    this.projects = res.data.projects;
                })
                .catch((error) => {
                    console.error(error);
                    toast.error('Failed to fetch projects. Please try again later.');
                })
            },
        }
    }
</script>