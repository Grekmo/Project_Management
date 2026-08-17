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
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                               <small class="text-muted">
                                    Projects
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

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Tasks 
                                </small>
                                <h2 class="fw-bold">
                                    {{ dashboard.myTasks }}
                                </h2>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-list-check text-success fs-2"></i>
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
            <div class="col-lg-6">
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
            <div class="col-lg-6">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold">
                            Tasks Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="taskChart"></canvas>
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
                    myTasks:0,
                    employees:0,
                    managers:0,

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
        },

        methods: {
            getDashboard(){
                api.get('/employee/dashboard')
                .then((res)=>{
                    this.dashboard=res.data;
                    this.renderChart();
                })
                .catch((error)=>{
                    console.log(error);
                });
            },

            renderChart() {
                const ctx = document.getElementById('projectChart');
                const task = document.getElementById('taskChart');

                if (this.taskChart) {
                    this.taskChart.destroy();
                }
                this.taskChart = new chart(task, {
                    type: 'bar', //  'bar', 'pie', 'radar', 'line', 'doughnut'
                    data: {
                        labels: [
                            'Completed',
                            'In Progress',
                            'Pending'
                        ],
                        datasets: [
                            {
                                label: 'My Tasks',
                                data: [
                                    this.dashboard.completedTasks,
                                    this.dashboard.progressTasks,
                                    this.dashboard.pendingTasks
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
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                if (this.projectChart) {
                    this.projectChart.destroy();
                }
                this.projectChart = new chart(ctx, {
                })

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
                                label: 'My Projects',
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
        }
    }

</script>