<template>
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-bell-fill text-primary me-2"></i>
                    Notifications
                </h2>

                <p class="text-secondary mb-0">
                    Manage all your notifications in one place.
                </p>
            </div>

            <button
                type="button"
                class="btn btn-primary shadow-sm"
                @click="markAllAsRead"
                :disabled="unreadNotifications === 0"
            >
                <i class="bi bi-check2-all me-1"></i>
                Mark all as read
            </button>
        </div>

        <!-- Statistics -->
        <div class="row g-4 mb-4">

            <!-- Total -->
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Total Notifications
                                </small>

                                <h2 class="fw-bold mb-0">
                                    {{ notifications.length }}
                                </h2>
                            </div>

                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-bell-fill text-primary fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unread -->
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Unread Notifications
                                </small>

                                <h2 class="fw-bold mb-0">
                                    {{ unreadNotifications }}
                                </h2>
                            </div>

                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-bell text-warning fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Read -->
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Read Notifications
                                </small>

                                <h2 class="fw-bold mb-0">
                                    {{ notifications.length - unreadNotifications }}
                                </h2>
                            </div>

                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-check-circle-fill text-success fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="card border-0 shadow rounded-4">

            <!-- Card Header -->
            <div class="card-header bg-white border-0 p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-inbox-fill text-primary me-2"></i>
                        All Notifications
                    </h5>

                    <!-- Filters -->
                    <div class="btn-group" role="group">

                        <button
                            type="button"
                            class="btn btn-sm"
                            :class="activeFilter === 'all' ? 'btn-primary' : 'btn-outline-primary'"
                            @click="activeFilter = 'all'"
                        >
                            All
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm"
                            :class="activeFilter === 'unread' ? 'btn-primary' : 'btn-outline-primary'"
                            @click="activeFilter = 'unread'"
                        >
                            Unread
                            <span v-if="unreadNotifications > 0">
                                ({{ unreadNotifications }})
                            </span>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm"
                            :class="activeFilter === 'read' ? 'btn-primary' : 'btn-outline-primary'"
                            @click="activeFilter = 'read'"
                        >
                            Read
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">

                <!-- Loading -->
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary"></div>

                    <p class="text-muted mt-2 mb-0">
                        Loading notifications...
                    </p>
                </div>

                <!-- Empty -->
                <div
                    v-else-if="filteredNotifications.length === 0"
                    class="text-center py-5"
                >
                    <i class="bi bi-bell-slash fs-1 text-muted"></i>

                    <h5 class="fw-bold mt-3">
                        No notifications found
                    </h5>

                    <p class="text-muted mb-0">
                        You don't have any notifications in this category.
                    </p>
                </div>

                <!-- Notifications List -->
                <div v-else class="list-group list-group-flush">

                    <div
                        v-for="notification in filteredNotifications"
                        :key="notification.id"
                        class="list-group-item p-3"
                    >

                        <div class="d-flex align-items-start gap-3">

                            <!-- Icon -->
                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                :class="notification.read_at
                                    ? 'bg-secondary bg-opacity-10'
                                    : 'bg-primary bg-opacity-10'"
                                style="width: 48px; height: 48px;"
                            >
                                <i
                                    v-if="notification.data.task_id"
                                    class="bi bi-list-check fs-4"
                                    :class="notification.read_at ? 'text-secondary' : 'text-primary'"
                                ></i>

                                <i
                                    v-else-if="notification.data.project_id"
                                    class="bi bi-folder-fill fs-5"
                                    :class="notification.read_at ? 'text-secondary' : 'text-primary'"
                                ></i>

                                <i
                                    v-else
                                    class="bi bi-bell-fill fs-5 text-secondary"
                                ></i>
                            </div>

                            <!-- Content -->
                            <div
                                class="flex-grow-1"
                                role="button"
                                @click="openNotification(notification)"
                            >
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">

                                    <h6 class="fw-bold mb-0">
                                        {{ notification.data.title }}
                                    </h6>

                                    <span
                                        class="badge rounded-pill px-3 py-2"
                                        :class="notification.read_at ? 'bg-secondary' : 'bg-primary'"
                                    >
                                        {{ notification.read_at ? 'Read' : 'Unread' }}
                                    </span>
                                </div>
                                <p class="text-secondary mb-2">
                                    {{ notification.data.message }}
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ formatDate(notification.created_at) }}
                                </small>
                            </div>

                            <!-- Delete -->
                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm rounded-circle flex-shrink-0"
                                title="Delete notification"
                                @click="deleteNotification(notification)"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-white border-0 p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <small class="text-muted">
                        Showing {{ filteredNotifications.length }}
                        of {{ notifications.length }} notifications
                    </small>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                        @click="fetchNotifications"
                        :disabled="loading"
                    >
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import api from '@/services/axios.js';
import { useToast } from 'vue-toastification';

const toast = useToast();

export default {
    name: 'NotificationsView',

    data() {
        return {
            notifications: [],
            unreadNotifications: 0,
            activeFilter: 'all',
            loading: false,
        };
    },

    computed: {
        filteredNotifications() {
            if (this.activeFilter === 'unread') {
                return this.notifications.filter(
                    notification => !notification.read_at
                );
            }
            if (this.activeFilter === 'read') {
                return this.notifications.filter(
                    notification => notification.read_at
                );
            }
            return this.notifications;
        }
    },

    mounted() {
        this.fetchNotifications();
    },

    methods: {

        async fetchNotifications() {
            this.loading = true;

            try {
                const res = await api.get('/notifications');

                this.notifications = res.data.notifications;
                this.unreadNotifications = res.data.unreadNotifications;

            } catch (error) {
                console.error(error);
                toast.error('Unable to load notifications.');
            } finally {
                this.loading = false;
            }
        },

        async markAllAsRead() {
            try {
                const res = await api.post('/notifications/all-read');

                this.notifications.forEach(notification => {
                    if (!notification.read_at) {
                        notification.read_at = new Date().toISOString();
                    }
                });

                this.unreadNotifications = 0;

                toast.success(res.data.message);

            } catch (error) {
                console.error(error);
                toast.error('Unable to mark notifications as read.');
            }
        },

        async deleteNotification(notification) {
            try {
                const res = await api.delete(`/notifications/${notification.id}`);

                this.notifications = this.notifications.filter(
                    item => item.id !== notification.id
                );

                if (!notification.read_at) {
                    this.unreadNotifications = Math.max(0,this.unreadNotifications - 1);
                }

                toast.success(res.data.message);

            } catch (error) {
                console.error(error);
                toast.error('Unable to delete notification.');
            }
        },

        async openNotification(notification) {
            try {
                // Mark as read
                if (!notification.read_at) {
                    await api.post(`/notifications/${notification.id}/read`);

                    notification.read_at = new Date().toISOString();

                    this.unreadNotifications = Math.max(
                        0,
                        this.unreadNotifications - 1
                    );
                }

                const taskId = notification.data.task_id;
                const projectId = notification.data.project_id;

                // Task Notification
                if (this.$route && taskId && this.getRole() === 'employee') {
                    await api.get(`/employee/show/tasks/${taskId}`);

                    await this.$router.push({
                        name: 'employee.show.tasks',
                        params: { id: taskId }
                    });

                    return;
                }

                // Project Notification
                if (projectId) {
                    if (this.getRole() === 'admin') {

                        await api.get(`/projects/${projectId}`);

                        await this.$router.push({
                            name: 'admin.show.projects',
                            params: { id: projectId }
                        });

                    } else if (this.getRole() === 'manager') {
                        await api.get(`/manager/projects/${projectId}`);

                        await this.$router.push({
                            name: 'manager.show.projects',
                            params: { id: projectId }
                        });

                    } else if (this.getRole() === 'employee') {
                        await api.get(`/employee/projects/${projectId}`);

                        await this.$router.push({
                            name: 'employee.show.projects',
                            params: { id: projectId }
                        });
                    }
                }

            } catch (error) {
                console.error(error);

                if (error.response?.status === 404) {
                    toast.error('This item is no longer available.');
                } else {
                    toast.error('An error occurred while opening the notification.');
                }
            }
        },

        getRole() {
            const user = JSON.parse(localStorage.getItem('user'));
            return user?.role;
        },

        formatDate(date) {
            return new Date(date).toLocaleString();
        },

    }
};
</script>
