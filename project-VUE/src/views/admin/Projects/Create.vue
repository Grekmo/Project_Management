<template>
    <div class="main-layout">
        <div class="container py-4">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h3 class="fw-bold text-success mb-0">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Create Project
                    </h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="saveProject">
                        <ul v-if="Object.keys(errorList).length > 0" class="mb-4">
                            <li v-for="(error,index) in errorList" :key="index" class="text-danger">
                                {{ error[0] }}
                            </li>
                        </ul>
                        <div class="row">
                            <!-- Project Name -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-folder-fill text-warning me-2"></i>
                                    Project Name
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    placeholder="Enter Project Name"
                                    v-model="projects.name"
                                >
                            </div>
                               <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-flag-fill text-danger me-2"></i>
                                    Status
                                </label>
                                <select
                                    class="form-select form-select-lg"
                                    v-model="projects.status"
                                >
                                    <option value="">
                                        Select Status
                                    </option>

                                    <option value="to_do">
                                        To Do
                                    </option>

                                    <option value="in_progress">
                                        In Progress
                                    </option>

                                    <option value="completed">
                                        Completed
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-card-text text-info me-2"></i>
                                    Description
                                </label>
                                <textarea
                                    rows="4"
                                    class="form-control"
                                    placeholder="Write project description..."
                                    v-model="projects.description"
                                ></textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event text-success me-2"></i>
                                    Start Date
                                </label>
                                <input
                                    type="date"
                                    class="form-control form-control-lg"
                                    v-model="projects.start_date"
                                >
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-check text-danger me-2"></i>
                                    End Date
                                </label>
                                <input
                                    type="date"
                                    class="form-control form-control-lg"
                                    v-model="projects.end_date"
                                >
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-workspace text-primary me-2"></i>
                                    Manager
                                </label>
                                <select
                                    class="form-select form-select-lg"
                                    v-model="projects.manager_id"
                                >
                                    <option value="">
                                        Select Manager
                                    </option>
                                    <option
                                        v-for="manager in managers"
                                        :key="manager.id"
                                        :value="manager.id"
                                    >
                                        {{ manager.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people-fill text-primary me-2"></i>
                                    Employees
                                </label>
                                <div
                                    class="border rounded-3 p-3"
                                    style="max-height:220px;overflow-y:auto;"
                                >
                                    <div
                                        class="form-check mb-2"
                                        v-for="employee in employees"
                                        :key="employee.id"
                                    >
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :value="employee.id"
                                            v-model="projects.employee_ids"
                                            :id="'employee' + employee.id"
                                        >
                                        <label class="form-check-label" :for="'employee' + employee.id">
                                            {{ employee.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 py-4">
                            <div class="d-flex justify-content-end gap-3">
                                <RouterLink
                                    :to="{name:'admin.projects'}"
                                    class="btn btn-outline-secondary px-4"
                                >
                                    <i class="bi bi-arrow-left-circle me-2"></i>
                                    Cancel
                                </RouterLink>
                                <button type="submit" class="btn btn-success px-5">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Create Project
                                </button>
                            </div>
                        </div>
                    </form>
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
                managers: [],
                employees: [],
                projects: {
                    name: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    manager_id: '',
                    employee_ids: [],
                    // v-model : hiya l box  wl  :value="" hiya liakan7toha fhad l box
                },
            }
        },
        mounted() {
            this.getManagers();
            this.getEmployees();
        },

        methods: {

            getEmployees() {
                api.get('/employees-list')
                .then( res=> {
                    this.employees = res.data.employees;
                })
                .catch(error => {
                    console.log(error.response.data.message);
                })
            },

            getManagers(){
                api.get('/managers')
                .then(res => {
                    //console.log(res.data.managers);
                    this.managers = res.data.managers;
                })
                .catch(error => {
                    console.log(error);
                })
            },

            saveProject() {
                api.post('/projects', this.projects)
                .then(res => {

                    toast.success(res.data.message);
                    this.projects = {
                        name: '',
                        description: '',
                        start_date: '',
                        end_date: '',
                        manager_id: '',
                        employee_ids: [],
                    }
                    this.errorList = {};

                    if (res.data.status === 200) {
                        this.$router.push( {name: 'admin.projects'} );
                    }
                })
                .catch((error) => {  //Makansst3mloch .catch(function (error)) because function katbdel l 9ima dyal this

                            console.log(error.response.data.errors);
                            this.errorList = error.response.data.errors;
                            toast.error('Please correct the errors in the form.');
                        
                        /*  console.log(error.response.data);
                            console.log(error.response.status);
                            console.log(error.response.headers);
                            
                        */
                });
            }
        }
    }
</script>