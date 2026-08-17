<template>
    <div class="main-layout">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">
                        <i class="bi bi-people-fill text-primary"></i>
                        Users Management
                    </h2>
                    <p class="text-secondary mb-0">
                        Manage Employees & Managers
                    </p>
                </div>

                <RouterLink :to="{ name:'admin.create.users' }" class="btn btn-success btn-lg shadow-sm">
                    <i class="bi bi-person-plus-fill"></i>
                    Add User
                </RouterLink>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" v-for="user in users" :key="user.id">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    {{ user.name }}
                                </h5>
                                <span class="badge rounded-pill"
                                    :class="{
                                        'bg-success': user.role == 'employee',
                                        'bg-warning text-dark': user.role == 'manager',
                                        'bg-danger': user.role == 'admin'
                                    }"
                                >
                                    {{ user.role }}
                                </span>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-person-vcard-fill"></i>
                                        CIN
                                    </small>
                                    <div>{{ user.cin }}</div>
                                </div>

                                <div class="col-6 mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-telephone-fill"></i>
                                        Phone
                                    </small>
                                    <div>{{ user.phone }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-envelope-fill"></i>
                                    Email
                                </small>
                                <div>{{ user.email }}</div>
                            </div>

                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-card-text"></i>
                                    Description
                                </small>
                                <div>{{ user.description }}</div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center gap-3">
                                <RouterLink
                                    :to="{ name:'admin.show.users', params:{ id:user.id } }"
                                    class="btn btn-outline-primary rounded-circle"
                                >
                                    <i class="bi bi-eye-fill"></i>
                                </RouterLink>
                                <RouterLink
                                    :to="{ name:'admin.edit.users', params:{ id:user.id } }"
                                    class="btn btn-outline-warning rounded-circle"
                                >
                                    <i class="bi bi-pencil-fill"></i>
                                </RouterLink>
                                <button @click="deleteUser(user.id)" class="btn btn-outline-danger rounded-circle">
                                    <i class="bi bi-trash-fill"></i>
                                </button>

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
                users: {
                    /*name: '',
                    role: '',
                    cin: '',
                    phone: '',
                    email: '',
                    description: '',
                    password: '',*/
                },
            }
        },

        mounted() {
            this.getUsers();
        },

        methods: {

            getUsers () {
                api.get('/users')
                .then( res => {
                    console.log(res.data),
                    this.users = res.data.users;
                })
                .catch((error) => {
                    if (error.response.data.status === 404) {
                        this.error
                    }
                })
            },

            deleteUser (userID) {
                if (!confirm('Are you sure you want to delete this user ? ')){
                    return
                }
                api.delete(`/users/${userID}`)
                .then((res) => {
                    toast.success(res.data.message);
                    this.getUsers();
                })
                .catch(error => {
                    if (error.response.data.status === 404) {
                        toast.error(error.response.data.message);
                    }
                })
            }

        }
    }
</script>