<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-dark text-white text-center py-4">
                        <i class="bi bi-person-circle display-1"></i>
                        <h2 class="mt-2">{{ users.name }}</h2>
                        <span class="badge fs-6"
                            :class="{
                                'bg-success': users.role == 'employee',
                                'bg-warning text-dark': users.role == 'manager',
                                'bg-danger': users.role == 'admin'
                            }">
                            {{ users.role }}
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-secondary">
                                    <i class="bi bi-person-vcard-fill me-2"></i>
                                    CIN
                                </h6>
                                <p class="fw-semibold">{{ users.cin }}</p>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-secondary">
                                    <i class="bi bi-telephone-fill me-2"></i>
                                    Phone
                                </h6>
                                <p class="fw-semibold">{{ users.phone }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-secondary">
                                <i class="bi bi-envelope-fill me-2"></i>
                                Email
                            </h6>
                            <p class="fw-semibold">{{ users.email }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-secondary">
                                <i class="bi bi-card-text me-2"></i>
                                Description
                            </h6>
                            <p class="fw-semibold">{{ users.description }}</p>
                        </div>

                        <div class="row mb-4">

                        <!-- Projects -->
                        <div class="col-md-6">
                            <h6 class="text-secondary">
                                <i class="bi bi-briefcase-fill"></i>
                                Projects
                            </h6>
                            <ul class="list-group" v-if="users.role === 'employee'">
                                <li v-for="project in users.projects" :key="project.id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-folder2-open me-2"></i>
                                        {{ project.name }}
                                    </span>
                                    <span
                                        class="badge"
                                        :class="{
                                            'bg-warning text-dark': project.status === 'to_do',
                                            'bg-primary': project.status === 'in_progress',
                                            'bg-success': project.status === 'completed'
                                        }"
                                    >
                                        {{ projectStatus.find(statuItem => statuItem.value === project.status)?.label }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <!-- Tasks -->
                        <div class="col-md-6">
                            <h6 class="text-secondary">
                                <i class="bi bi-list-check text-success me-2"></i>
                                Tasks
                            </h6>
                            <ul class="list-group" v-if="users.role === 'employee'">
                                <li v-for="task in users.tasks" :key="task.id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-check2-square text-success me-2"></i>
                                        {{ task.name }}
                                    </span>
                                    <span
                                        class="badge"
                                        :class="{
                                            'bg-warning text-dark': task.status === 'pending',
                                            'bg-primary': task.status === 'in_progress',
                                            'bg-success': task.status === 'completed'
                                        }"
                                    >
                                        {{ taskStatus.find(statuItem => statuItem.value === task.status)?.label }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between">
                            <RouterLink :to="{ name:'manager.team' }" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i>
                                Back
                            </RouterLink>
                            
                        </div>
                    </div>
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
                users: {
                    name: '',
                    cin: '',
                    email: '',
                    phone: '',
                    password: '',
                    role: '',
                    description: '',
                    projects: [],  
                    tasks: [],  
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
                api.get(`/manager/show/user/${userID}`)
                .then( res => {
                    console.log(res.data.user.projects);
                    this.users = res.data.user;                              
                })
                .catch(error => {
                    console.log(error.response.data.message);
                    
                })
            }
        },
        
    }
</script>