<template>
    <div class="main-layout">

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white py-3">
                            <h3 class="mb-0">
                                <i class="bi bi-person-plus-fill me-2"></i>
                                Create New User
                            </h3>
                            <small class="text-light">
                                Add a new Employee or Manager
                            </small>
                        </div>
                        <div class="card-body p-4">
                            <ul v-if="Object.keys(errorList).length > 0" class="mb-4">
                                <li v-for="(error,index) in errorList" :key="index" class="text-danger">
                                    {{ error[0] }}
                                </li>
                            </ul>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-person-fill me-2 text-primary"></i>
                                        Name
                                    </label>
                                    <input type="text" v-model="users.name" class="form-control form-control-lg">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-person-vcard-fill me-2 text-success"></i>
                                        CIN
                                    </label>
                                    <input type="text" v-model="users.cin" class="form-control form-control-lg">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-envelope-fill me-2 text-danger"></i>
                                        Email
                                    </label>
                                    <input type="email" v-model="users.email" class="form-control form-control-lg">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-telephone-fill me-2 text-info"></i>
                                        Phone
                                    </label>
                                    <input type="text" v-model="users.phone" class="form-control form-control-lg">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-lock-fill me-2 text-warning"></i>
                                        Password
                                    </label>
                                    <input type="password" v-model="users.password" class="form-control form-control-lg">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-shield-lock-fill me-2 text-secondary"></i>
                                        Role
                                    </label>
                                    <select v-model="users.role" class="form-select form-select-lg">
                                        <option value="">Select Role</option>
                                        <option v-for="role in roles" :key="role.value" :value="role.value">
                                            {{ role.label }}
                                        </option> 
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-card-text me-2 text-primary"></i>
                                    Description
                                </label>
                                <textarea
                                    rows="4"
                                    v-model="users.description"
                                    class="form-control"
                                    placeholder="Write a short description..."
                                ></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <RouterLink :to="{ name:'admin.users' }" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left"></i>
                                    Back
                                </RouterLink>
                                <button type="button" @click="saveUser()" class="btn btn-success btn-lg px-5">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Create User
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
                //model: {}; Ila kan 3endna kter mn object
                errorList: {},
                error: '',
                roles: [
                    {value: 'employee', label: 'Employee'},
                    {value: 'manager', label: 'Manager'},
                    {value: 'admin', label: 'Admin'},
                ],
                //employees: [],
                users: {
                    name: '',
                    cin: '',
                    email: '',
                    phone: '',
                    password: '',
                    role: '',
                    description: '',     
                },
            }
        },
        mounted() {
            
        },

        methods: {

            saveUser() {
                api.post('/users', this.users)
                .then( res => {
                    console.log(res.data);
                    toast.success(res.data.message);
                    this.users = {
                        name: '',
                        cin: '',
                        email: '',
                        phone: '',
                        password: '',
                        role: '',
                        description: '',
                    };
                    this.errorList = {};
                    this.$router.push( {name: 'admin.users'} );
                })
                .catch( error => {
                    console.log(error.response.data.errors);
                    toast.error('An error occurred while saving the user.');
                    this.errorList = error.response.data.errors;
                })
            }
        }
    }
</script>