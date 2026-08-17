<template>
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="bg-dark text-white text-center py-5">
                <div
                    class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mx-auto mb-3 fw-bold"
                    style="width:100px;height:100px;font-size:40px;"
                >
                    {{ employee.name.charAt(0).toUpperCase() }}
                </div>
                <h2 class="fw-bold mb-2">
                    {{ employee.name }}
                </h2>
                <span class="badge bg-success fs-6">
                    {{ employee.role }}
                </span>
            </div>
            <div class="card-body p-4">

                <!-- Personal Information -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light rounded-4">
                            <div class="card-body">
                                <h6 class="text-secondary">
                                    <i class="bi bi-envelope-fill me-2"></i>
                                    Email
                                </h6>
                                <p class="fw-semibold mb-0">
                                    {{ employee.email }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light rounded-4">
                            <div class="card-body">
                                <h6 class="text-secondary">
                                    <i class="bi bi-telephone-fill me-2"></i>
                                    Phone
                                </h6>
                                <p class="fw-semibold mb-0">
                                    {{ employee.phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light rounded-4">
                            <div class="card-body">
                                <h6 class="text-secondary">
                                    <i class="bi bi-person-vcard-fill me-2"></i>
                                    CIN
                                </h6>
                                <p class="fw-semibold mb-0">
                                    {{ employee.cin }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light rounded-4">
                            <div class="card-body">
                                <h6 class="text-secondary">
                                    <i class="bi bi-calendar-check-fill me-2"></i>
                                    Total Tasks
                                </h6>
                                <p class="fw-bold fs-5 mb-0">
                                    {{ employee.tasks.length }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <h5 class="mb-3">
                                <i class="bi bi-card-text me-2"></i>
                                Description
                            </h5>
                            <p class="mb-0">
                                {{ employee.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Progress -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="bi bi-graph-up-arrow text-success me-2"></i>
                            Progress
                        </h5>
                        <div class="progress mb-2" style="height:12px;">
                            <div
                                class="progress-bar bg-success"
                                :style="{ width: employee.progress + '%' }"
                            >
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                {{ employee.progress }}%
                                Completed
                            </small>
                            <small class="fw-bold">
                                {{ employee.completedTasks }}
                                /
                                {{ employee.tasks.length }}
                                Tasks
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Projects -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="bi bi-folder-fill text-warning me-2"></i>
                            Projects
                        </h5>
                        <div
                            v-for="project in employee.projects"
                            :key="project.id"
                            class="border rounded-3 p-3 mb-2"
                        >
                            <div class="fw-bold">
                                {{ project.name }}
                            </div>
                            <span
                                class="badge"
                                :class="{
                                'bg-warning text-dark': project.status=='to_do',
                                'bg-primary': project.status=='in_progress',
                                'bg-success': project.status=='completed'
                            }"
                            >
                                {{ projectStatus.find(projectItem => projectItem.value === project.status)?.label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tasks -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="bi bi-list-task text-primary me-2"></i>
                            Tasks
                        </h5>
                        <div
                            v-for="task in employee.tasks"
                            :key="task.id"
                            class="d-flex justify-content-between align-items-center border-bottom py-2"
                        >
                            <span>
                                {{ task.name }}
                            </span>
                            <span
                                class="badge"
                                :class="{
                                    'bg-success': task.status==='completed',
                                    'bg-primary': task.status==='in_progress',
                                    'bg-warning text-dark': task.status==='pending'
                                }"
                            >
                                {{ taskStatus.find(statuItem => statuItem.value === task.status)?.label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <div class="d-flex justify-content-end gap-2">
                    <RouterLink
                        :to="{name:'employee.teamMember'}"
                        class="btn btn-outline-secondary"
                    >
                        Back
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
                employee: {
                    name: "",
                    cin: "",
                    email: "",
                    phone: "",
                    role: "",
                    description: "",
                    projects: [],
                    tasks: [],
                    progress: 0,
                    completedTasks: 0
                },
                taskStatus: [
                    {value: 'pending', label: 'Pending'},
                    {value: 'in_progress', label: 'In Progress'},
                    {value: 'completed', label: 'Completed'},
                ],
                projectStatus: [
                    {value: 'to_do', label: 'To Do'},
                    {value: 'in_progress', label: 'In Progress'},
                    {value: 'completed', label: 'Completed'},
                ],
            }
        },

        mounted(){
            this.userID = this.$route.params.id;
            this.getUser(this.userID);
        },

        methods: {

            getUser(userID) {
                api.get(`/employee/member/show/${userID}`)
                .then( res => {
                    console.log(res.data.user);
                    this.employee = res.data.user;                              
                })
                .catch(error => {
                    console.log(error.response.data.message);
                    
                })
            }
        },
        
    }
</script>