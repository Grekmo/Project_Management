<template>
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div
                class="card-header"
                :class="{
                    'bg-warning text-dark': projects.status=='to_do',
                    'bg-primary text-white': projects.status=='in_progress',
                    'bg-success text-white': projects.status=='completed'
                }"
            >
                <h3>
                    <i class="bi bi-folder-fill me-2"></i>
                    Project Details
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">
                            <i class="bi bi-folder-fill text-warning me-2"></i>
                            Project Name
                        </h6>
                        <h5 class="fw-bold">
                            {{ projects.name }}
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">
                            Status
                        </h6>
                        <span
                            class="badge fs-6"
                            :class="{
                                'bg-warning text-dark': projects.status=='to_do',
                                'bg-primary': projects.status=='in_progress',
                                'bg-success': projects.status=='completed'
                            }"
                        >
                            {{ projectStatus.find(statuItem => statuItem.value === projects.status)?.label }}
                        </span>
                    </div>
                </div>
                <hr>
                <h6 class="text-muted">
                    <i class="bi bi-card-text text-info me-2"></i>
                    Description
                </h6>
                <p>
                    {{ projects.description }}
                </p>
                <hr>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6>
                            <i class="bi bi-person-workspace text-primary me-2"></i>
                            Manager
                        </h6>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-3 text-center">
                                <i class="bi bi-person-circle fs-1 text-primary"></i>
                                <h6 class="mt-2 mb-1">
                                    {{ projects.manager?.name }}
                                </h6>
                                <small class="text-muted">
                                    {{ projects.manager?.email }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>
                            <i class="bi bi-calendar-event text-success me-2"></i>
                            Start Date
                        </h6>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-4 text-center">
                                {{ formDate(projects.start_date) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>
                            <i class="bi bi-calendar-check text-danger me-2"></i>
                            End Date
                        </h6>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-4 text-center">
                                {{ formDate(projects.end_date) }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h6 class="mb-3">
                    <i class="bi bi-people-fill text-primary me-2"></i>
                    Employees
                    <span class="badge bg-primary ms-2">
                        {{ projects.employees.length }}
                    </span>
                </h6>
                <div class="row g-3">
                    <div
                        class="col-md-4"
                        v-for="employee in projects.employees"
                        :key="employee.id"
                    >
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-3">
                                <i class="bi bi-person-circle fs-2 text-primary"></i>
                                <h6 class="mt-2 mb-1">
                                    {{ employee.name }}
                                </h6>
                                <small class="text-muted">
                                    {{ employee.email }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h6 class="mb-3">
                    <i class="bi bi-list-task text-success me-2"></i>
                    Tasks
                    <span class="badge bg-success ms-2">
                        {{ projects.tasks?.length }}
                    </span>
                </h6>
                <div class="row g-3">
                    <div
                        class="col-md-6"
                        v-for="task in projects.tasks"
                        :key="task.id"
                    >
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">
                                        {{ task.name }}
                                    </h6>
                                    <span
                                        class="badge"
                                        :class="{
                                            'bg-warning text-dark': task.status=='pending',
                                            'bg-primary': task.status=='in_progress',
                                            'bg-success': task.status=='completed'
                                        }"
                                    >
                                        {{ taskStatus.find(statuItem => statuItem.value === task.status)?.label }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-3">
                                    {{ task.description }}
                                </p>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="bi bi-person-fill text-primary me-1"></i>
                                            {{ task.employee?.name }}
                                        </small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event text-danger me-1"></i>
                                            {{ formDate(task.end_date) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="projects.tasks.length==0"
                    class="alert alert-secondary mt-3 text-center"
                >
                    <i class="bi bi-info-circle me-2"></i>
                    No Tasks Found
                </div>
                <div
                    v-if="projects.employees.length==0"
                    class="alert alert-secondary mt-3 text-center"
                >
                    <i class="bi bi-info-circle me-2"></i>
                    No Employees Assigned
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <div class="d-flex justify-content-end gap-2">
                    <RouterLink
                        :to="{name:'admin.projects'}"
                        class="btn btn-outline-secondary"
                    >
                        Back
                    </RouterLink>
                    <RouterLink
                        :to="{name:'admin.edit.projects',params:{id:projects.id}}"
                        class="btn btn-warning"
                    >
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
                errorList: {},
                projects: {
                    name: '',
                    //status: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    manager_id: '',
                    manager: {},
                    tasks:[],
                    employee_ids: [],
                    employees: [],
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
            this.projectID = this.$route.params.id;
            this.getProject(this.projectID);
        },

        methods: {

            formDate(date) {
                return date.split('T')[0];
            },

            getProject(projectID) {
                api.get(`/projects/${projectID}`)
                .then( res => {
                    console.log(res.data);
                    this.projects = res.data.project;                              
                })
                .catch(error => {
                    console.log(error.response.data.message);
                    
                })
            }
        },
        
    }
</script>