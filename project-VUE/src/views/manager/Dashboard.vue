<template>
    <div class="container-fluid py-4">
        <!-- Welcome -->
        <div class="mb-4">
            <h2 class="fw-bold">
                👋 Welcome {{ user.name }}
            </h2>
            <p class="text-muted">
                Here's an overview of your workspace.
            </p>
        </div>

        <!-- Statistics -->
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                               <small class="text-muted">
                                    My Projects
                                </small>
                                <h2 class="fw-bold">
                                    {{ dashboard.myProjects }}
                                </h2>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-folder-fill text-primary fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Tasks 
                                </small>
                                <h2 class="fw-bold">
                                    {{ dashboard.tasks }}
                                </h2>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-list-check text-success fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Team
                                </small>
                                <h2 class="fw-bold">
                                    {{ dashboard.team }}
                                </h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-people-fill text-warning fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>

        <!-- Project Statistics -->
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold">
                            <i class="bi bi-folder-fill text-primary me-2"></i>
                            Projects
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Completed</span>
                            <span class="badge bg-success">
                                {{ dashboard.completedProjects }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>In Progress</span>
                            <span class="badge bg-primary">
                                {{ dashboard.progressProjects }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>To Do</span>
                            <span class="badge bg-warning text-dark">
                                {{ dashboard.todoProjects }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold">
                            <i class="bi bi-list-task text-success me-2"></i>
                            Tasks
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Completed</span>
                            <span class="badge bg-success">
                                {{ dashboard.completedTasks }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>In Progress</span>
                            <span class="badge bg-primary">
                                {{ dashboard.progressTasks }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pending</span>
                            <span class="badge bg-warning text-dark">
                                {{ dashboard.pendingTasks }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mx-auto">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold">
                            Projects Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    //import Navbar from '@/components/layout/Navbar.vue'
    //import Sidebar from '@/components/layout/Sidebar.vue' // @ => ../../
    import api from '@/services/axios';
    import { useToast } from 'vue-toastification';
    import chart from 'chart.js/auto';

    const toast = useToast();

    export default {
        data() {
            return {
                user: {
                    name: '',
                    role: ''
                },
                dashboard:{

                    myProjects:0,
                    tasks:0,
                    team:0,

                    completedProjects:0,
                    progressProjects:0,
                    todoProjects:0,

                    completedTasks:0,
                    progressTasks:0,
                    pendingTasks:0,
                },
                projectChart: null,
            }
        },
       
        mounted() {
            //toast.success('Connected')
            this.user = JSON.parse(localStorage.getItem('user'));
            this.getDashboard();
            this.getUser();
        },

        methods: {
            getDashboard(){
                api.get('/managerDashboard')
                .then((res)=>{
                    console.log(res.data);
                    this.dashboard=res.data.dashboard;
                    this.renderChart();
                })
                .catch((error)=>{
                    console.log(error);
                });
            },

            renderChart() {
                const ctx = document.getElementById('projectChart');
                if (this.projectChart) {
                    this.projectChart.destroy();
                }
                this.projectChart = new chart(ctx, {
                    type: 'doughnut', //  'bar', 'pie', 'radar', 'line', 'doughnut'
                    data: {
                        labels: [
                            'Completed',
                            'In Progress',
                            'To Do'
                        ],
                        datasets: [
                            {
                                label: 'Projects',
                                data: [
                                    this.dashboard.completedProjects,
                                    this.dashboard.progressProjects,
                                    this.dashboard.todoProjects
                                ],

                                backgroundColor: [
                                    '#198754',
                                    '#0d6efd',
                                    '#ffc107'
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            },

            getUser() {
                api.get('/user')
                .then((res)=>{
                    this.user=res.data;
                })
                .catch((error)=>{
                    console.log(error);
                });
            }
        }
    }

</script>