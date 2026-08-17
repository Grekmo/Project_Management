<template>
    <div class="main-layout">
        <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">
                        <i class="bi bi-people-fill text-primary"></i>
                        Team Members
                    </h2>
                    <p class="text-secondary mb-0">
                        Display all team members and their progress in projects and tasks.
                    </p>
                </div>
            </div>
        <div class="card shadow-sm border-0 rounded-4 mb-3"
            v-for="employee in employees"
            :key="employee.id">
            <div class="card-body">
                <div v-if="error" class="alert alert-warning">
                    {{ error }}
                </div>
                <div class="d-flex justify-content-between align-items-start">
                    <!-- Left -->
                    <div class="d-flex">
                        <!-- Avatar -->
                        <div
                            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                            style="width:55px;height:55px;font-size:22px;"
                        >
                            {{ employee.name.charAt(0).toUpperCase() }}
                        </div>

                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold">
                                {{ employee.name }}
                            </h5>
                            <span class="badge bg-success mb-3">
                                {{ employee.role }}
                            </span>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-folder-fill me-2"></i>

                                {{ employee.projects.length }}
                                Projects
                            </div>
                            <div class="small text-muted mb-3">
                                <i class="bi bi-list-check me-2"></i>

                                {{ employee.tasks.length }}
                                Tasks
                            </div>
                            <!-- Progress -->
                            <div class="progress" style="height:8px;width:240px;">
                                <div
                                    class="progress-bar bg-success"
                                    :style="{width: employee.progress + '%'}"
                                ></div>
                            </div>
                            <small class="text-muted">
                                {{ employee.progress }}% Completed
                            </small>
                        </div>
                    </div>

                    <!-- Right -->
                    <RouterLink
                        :to="{name:'employee.show.users',params:{id:employee.id}}"
                        class="btn btn-outline-primary"
                    >
                        <i class="bi bi-eye-fill"></i>
                    </RouterLink>
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
                employees: [],
                error: '',
            }
        },

        mounted() {
            this.getUsers();
        },

        methods: {

            getUsers () {
                api.get('/employee/team-members')
                .then( res => {
                    console.log(res.data),
                    this.employees = res.data.teamMembers;
                })
                .catch((error) => {
                    if (error.response.data.status === 404) {
                        this.error = error.response.data.message;
                    }
                })
            },
        }
    }
</script>