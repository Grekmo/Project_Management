<template>
    <div class="main-layout">
        <div class="container py-4">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark text-white py-3">
                    <h3 class="fw-bold mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Project
                    </h3>
                </div>
                <div class="card-body">
                    <ul v-if="Object.keys(errorList).length > 0" class="mb-4">
                        <li v-for="(error,index) in errorList" :key="index" class="text-danger">
                            {{ error[0] }}
                        </li>
                    </ul>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-folder-fill text-warning me-2"></i>
                                    Project Name
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    v-model="projects.name"
                                >
                            </div>
                            <!-- Status -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-flag-fill text-danger me-2"></i>
                                    Status
                                </label>
                                <select
                                    class="form-select form-select-lg"
                                    v-model="projects.status"
                                >
                                    <option value="to_do">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-card-text text-info me-2"></i>
                                    Description
                                </label>
                                <textarea
                                    rows="4"
                                    class="form-control"
                                    v-model="projects.description"
                                ></textarea>
                            </div>

                            <!-- Dates -->
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

                            <!-- Manager -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-workspace text-primary me-2"></i>
                                    Manager
                                </label>
                                <select
                                    class="form-select form-select-lg"
                                    v-model="projects.manager_id"
                                >
                                    <option
                                        v-for="manager in managers"
                                        :key="manager.id"
                                        :value="manager.id"
                                    >
                                        {{ manager.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Employees -->
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
                                            :id="'employee'+employee.id"
                                        >
                                        <label
                                            class="form-check-label"
                                            :for="'employee'+employee.id"
                                        >
                                            {{ employee.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Tasks -->
                            <hr class="my-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-list-task text-primary me-2"></i>
                                    Project Tasks
                                </h5>
                                <span class="badge bg-primary rounded-pill">
                                    {{ projects.tasks?.length || 0 }}
                                </span>
                            </div>
                            <div class="table-responsive shadow-sm rounded-4 border">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Task</th>
                                            <th>Employee</th>
                                            <th>Status</th>
                                            <th>End Date</th>
                                            <th width="140" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="projects.tasks && projects.tasks.length">
                                        <tr
                                            v-for="task in projects.tasks"
                                            :key="task.id"
                                        >
                                            <td class="fw-semibold">
                                                {{ task.name }}
                                            </td>
                                            <td>
                                                <i class="bi bi-person-circle text-primary me-2"></i>
                                                {{ task.employee?.name }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge"
                                                    :class="{
                                                        'bg-warning text-dark': task.status=='pending',
                                                        'bg-primary': task.status=='in_progress',
                                                        'bg-success': task.status=='completed'
                                                    }"
                                                >
                                                    {{ taskStatus.find(s => s.value === task.status)?.label }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ formDate(task.end_date) }}
                                            </td>
                                            <td class="text-center">
                                                <RouterLink
                                                    class="btn btn-info btn-sm me-2"
                                                    :to="{name:'admin.show.tasks',params:{id:task.id}}"
                                                >
                                                    <i class="bi bi-eye-fill"></i>
                                                </RouterLink>
                                                <button class="btn btn-sm btn-warning me-2"  @click="openEditTaskModal(task)">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <button
                                                   @click="deleteTask(task.id)"
                                                    class="btn btn-sm btn-danger"
                                                >
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                                No Tasks Found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-success btn-lg rounded-pill px-5 shadow-sm" type="button" @click="openTaskModal">
                                    <i class="bi bi-plus-circle-fill me-2"></i>
                                    Add Task
                                </button>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-white border-0 py-4">
                            <div class="d-flex justify-content-end gap-3">
                                <RouterLink
                                    :to="{name:'admin.projects'}"
                                    class="btn btn-outline-secondary px-4"
                                >
                                    <i class="bi bi-arrow-left-circle me-2"></i>
                                    Cancel
                                </RouterLink>
                                <button type="submit" class="btn btn-warning px-5" @click="updateProject(projectID)">
                                    <i class="bi bi-pencil-square me-2"></i>
                                    Update Project
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Task Modal -->
    <div class="modal fade show" v-if="showTaskModal" style="display:block;background:rgba(0,0,0,.5)">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <!-- Header -->
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h4 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle-fill me-2" 
                            :class="isEditingTask ? 'bi bi-pencil-fill me-2' : 'bi bi-plus-circle-fill me-2'">
                        </i>
                        {{ isEditingTask ? 'Edit Task' : ' Create Task' }}
                    </h4>
                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        @click="closeTaskModal"
                    ></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <div class="row">
                        <ul v-if="Object.keys(errorList).length > 0" class="mb-4">
                            <li v-for="(error,index) in errorList" :key="index" class="text-danger">
                                {{ error[0] }}
                            </li>
                        </ul>
                        <!-- Task Name -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-list-task text-primary me-2"></i>
                                Task Name
                            </label>
                            <input
                                type="text"
                                class="form-control form-control-lg"
                                placeholder="Enter Task Name"
                                v-model="task.name"
                            >
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-flag-fill text-warning me-2"></i>
                                Status
                            </label>
                            <select
                                class="form-select form-select-lg"
                                v-model="task.status"
                            >
                                <option value="">
                                    Select Status
                                </option>
                                <option
                                    v-for="item in taskStatus"
                                    :key="item.value"
                                    :value="item.value"
                                >
                                    {{ item.label }}
                                </option>

                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-12 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text text-info me-2"></i>
                                Description
                            </label>
                            <textarea
                                rows="4"
                                class="form-control"
                                placeholder="Write Task Description..."
                                v-model="task.description"
                            ></textarea>
                        </div>

                        <!-- Employee -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-primary me-2"></i>
                                Employee
                            </label>
                            <select
                                class="form-select form-select-lg"
                                v-model="task.assigned_to"
                            >
                                <option value="">
                                    Select Employee
                                </option>
                                <option
                                    v-for="employee in projects.employees"
                                    :key="employee.id"
                                    :value="employee.id"
                                >
                                    {{ employee.name }}
                                </option>
                            </select>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event text-danger me-2"></i>
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

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" @click="closeTaskModal">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-success px-5"  @click="isEditingTask ? updateTask() : saveTask()">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ isEditingTask ? 'Update Task' : 'Save Task' }}
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
                projectID: '',
                managers: [],
                employees: [],
                task: {
                    name: '',
                    description: '',
                    end_date: '',
                    status: '',
                    assigned_to: '',
                    project_id: this.projectID,
                },
                showTaskModal: false, // KAN7OTO F MODAL
                isEditingTask: false,
                projects:{
                    name: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    manager_id: '',
                    employee_ids: [], // Kanstocker fiha les IDS dyal * employees: [], * bach n7ehom f checkbox 
                    employees: [],
                    // <label class="form-check-label " :for="'employee' + employee.id".. :for bach l click ykhdem l chekbox w name li 9damha
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

        mounted() {
            this.projectID = this.$route.params.id;
            this.getProject(this.projectID);
            this.getManagers();
            this.getEmployees();
        },

        methods: {

            formDate(date) {
                return date.split('T')[0];
            },

            openTaskModal() {
                this.isEditingTask = false;
                this.task = {
                    id: null,
                    name: '',
                    description: '',
                    end_date: '',
                    status: '',
                    assigned_to: '',
                    project_id: this.projectID,
                };
                this.showTaskModal = true;
            },

            openEditTaskModal(task){
                this.isEditingTask = true;
                this.task = {
                    id: task.id,
                    name: task.name,
                    description: task.description,
                    status: task.status,
                    end_date: task.end_date.split('T')[0],
                    assigned_to: task.assigned_to,
                    project_id: task.project_id,
                };
                this.showTaskModal = true;

            },

            saveTask() {
                this.task.project_id = this.projectID;
                api.post('/tasks', this.task)
                .then((res) => {
                    toast.success(res.data.message);
                    this.closeTaskModal();
                    this.getProject(this.projectID);
                })
                .catch((error) => {
                    toast.error(error.response.data.message);
                    this.errorList = error.response.data.errors;
                });
            },
            
            closeTaskModal() {
                this.showTaskModal = false ;
                this.isEditingTask = false;
                this.task = {
                    id: null,
                    name: '',
                    description: '',
                    status: '',
                    end_date: '',
                    assigned_to: '',
                    project_id: this.projectID,
                };
            },

            updateTask() {
                this.task.project_id = this.projectID;
                api.put(`/tasks/${this.task.id}`, this.task)
                .then( res => {
                    console.log(res.data);
                    toast.success(res.data.message);
                    this.closeTaskModal();
                    this.getProject(this.projectID);
                })
                .catch( error => {
                    console.log(error.response.data);
                    toast.error('Failed to update task.');
                    this.errorList = error.response.data.errors;
                })
            },

            getEmployees() {
                api.get('/employees-list')
                .then( res => {
                    console.log(res.data.employees);
                    this.employees = res.data.employees;
                })
                .catch(error => {
                    console.log(error.response.data.message);
                })
            },

            getManagers(){
                api.get('/managers')
                .then( res => {
                    //console.log(res.data.managers);
                    this.managers = res.data.managers;
                })
                .catch(error => {
                    console.log(error.response.data.message);
                })
            },


            getProject(projectID){
                api.get(`/projects/${projectID}`)
                .then( res => {
                    console.log(res.data.project);
                    this.projects = res.data.project;
                    //this.projects.teamMembers = res.data.projects.employees;

                    this.projects.employee_ids = this.projects.employees.map(employee => employee.id);
                    // map(employee => employee.id)  hiya LOOP Katrje3 liya mn kol employee li ja mn LARAVEL l ID dyalo 

                    this.projects.start_date = this.projects.start_date.split('T')[0];
                    this.projects.end_date = this.projects.end_date.split('T')[0];
                })
                .catch(error => {
                    console.log(error.response.data.message);
                })
            },
            
            updateProject(projectID){
                api.put(`/projects/${projectID}`, this.projects)
                .then( res => {
                    console.log(res.data);
                    this.errorList = {}; 
                    toast.success(res.data.message);
                    this.$router.push( {name: 'admin.projects' });
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errorList = error.response.data.errors;
                        toast.error('Please correct the errors in the form.');
                    }else{
                        console.log(error.response);
                    }
                })
            },

            deleteTask (taskID) {
                if (!confirm('Are you sure you want to delete this task ? ')){
                    return
                }
                api.delete(`/tasks/${taskID}`)
                .then((res) => {
                    toast.success(res.data.message);
                    this.getProject(this.projectID);
                })
                .catch(error => {
                    if (error.response.data.status === 404) {
                        toast.error(error.response.data.message);
                    }
                })
            }
        },
    }
</script>