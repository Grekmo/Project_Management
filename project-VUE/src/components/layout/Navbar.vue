<template>
    <nav class="navbar navbar-expand-lg navbar-custom px-4 py-3">
        <!-- Left -->
        <div class="d-flex align-items-center">
            <h4 class="fw-bold mb-0 text-white">
                Project Management
            </h4>
        </div>
        <!-- Right -->
        <div class="ms-auto d-flex align-items-center gap-4">
            <!-- Search -->
            <div class="position-relative">
                <i
                    class="bi bi-search position-absolute search-icon"
                ></i>
                <input
                    type="text"
                    class="form-control search-input"
                    placeholder="Search..."
                >
            </div>

            <!-- Notification -->   

            <div class="dropdown">
                <!-- Notification Button -->
                <button
                    class="btn notification-btn position-relative"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="bi bi-bell fs-5"></i>

                    <span
                        v-if="unreadNotifications > 0"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    >
                        {{ unreadNotifications }}
                    </span>
                </button>

                <!-- Dropdown Menu -->
                <div class="dropdown-menu dropdown-menu-end shadow p-0 border-0"
                    style="width: 360px; max-width: 90vw; background-color: #ffffff; color: #333333;">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-white text-dark">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Notifications</h6>
                            <small class="text-muted">
                                {{ notifications.length }} total
                            </small>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-link text-decoration-none p-0 text-primary"
                            @click="markAllAsRead"
                            :disabled="unreadNotifications === 0"
                        >
                            <i class="bi bi-check2-all me-1"></i>
                            Mark all as read
                        </button>
                    </div>

                    <!-- Notification List -->
                    <div
                        v-if="notifications.length === 0"
                        class="text-center text-muted p-4 bg-white"
                    >
                        <i class="bi bi-bell-slash fs-3"></i>
                        <p class="mb-0 mt-2">No notifications yet</p>
                    </div>

                    <div v-else class="notification-list" style="max-height: 350px; overflow-y: auto;">
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            class="d-flex align-items-center gap-2 p-3 border-bottom"
                            :style="{ backgroundColor: !notification.read_at ? '#f8f9fa' : '#ffffff' }"
                        >
                            <!-- Notification Content -->
                            <button
                                type="button"
                                class="btn p-0 border-0 text-start flex-grow-1 bg-transparent"
                                @click="openNotification(notification)"
                            >
                                <div class="d-flex align-items-start gap-2">
                                    <div class="text-primary fs-5">
                                        <i
                                            v-if="notification.data.task_id"
                                            class="bi bi-list-check"
                                        ></i>
                                        <i
                                            v-else-if="notification.data.project_id"
                                            class="bi bi-folder2-open"
                                        ></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            {{ notification.data.title }}
                                        </div>
                                        <p class="text-secondary small mb-1">
                                            {{ notification.data.message }}
                                        </p>
                                        <small :class="!notification.read_at ? 'text-danger fw-bold' : 'text-muted'">
                                            {{ notification.read_at ? 'Read' : 'New' }}
                                        </small>
                                    </div>
                                </div>
                            </button>

                            <!-- Delete Button -->
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger border-0 rounded-circle flex-shrink-0"
                                title="Delete notification"
                                @click="deleteNotification(notification)"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-3 text-center border-top bg-white">
                        <RouterLink
                            :to="{ name: 'notifications' }"
                            class="text-decoration-none fw-semibold small text-primary"
                        >
                            View all notifications
                            <i class="bi bi-arrow-right ms-1"></i>
                        </RouterLink>
                    </div>
                </div>
            </div>

            <!-- User -->
            <div class="dropdown">
                <button
                    class="btn user-btn d-flex align-items-center rounded-pill px-3"
                    data-bs-toggle="dropdown"
                >
                    <div class="avatar me-2">
                        {{ user.name?.charAt(0).toUpperCase() }}
                    </div>
                    <div class="text-start">
                        <div class="fw-semibold">
                            {{ user.name }}
                        </div>
                        <small class="fw-semibold ">
                            {{ capitalize(user.role) }}
                        </small>
                    </div>
                    <i class="bi bi-chevron-down ms-3"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                    <li>
                        <button
                            class="btn btn-light dropdown-item text-danger"
                            aria-expanded="false"
                            type="button"
                            @click="logOut"
                        >
                            <i class="bi bi-box-arrow-right me-2"></i>

                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

  import api from '@/services/axios.js';
  import { useToast } from 'vue-toastification';
  

  const toast = useToast();

  export default {
    data() {
      return {
        token: '',
        user: {
          name: '',
          role: ''
        },
        notifications: [],
        unreadNotifications: 0,
      }
    },

    mounted() {
      this.user = JSON.parse(localStorage.getItem('user')); // parse kan7owl string l object bach n9dro nst3mlo f vue
      this.token = localStorage.getItem('token');
      console.log('User data from localStorage:', this.user);
      console.log('Token from localStorage:', this.token);

      this.fetchNotifications();
    },

    methods: {

        fetchNotifications() {
            api.get('/notifications')
            .then((res) => {
                this.notifications = res.data.notifications;
                this.unreadNotifications = res.data.unreadNotifications;
            })
            .catch((error) => {
                console.log('Status:', error.response?.status);
                console.log('Data:', error.response?.data);
                console.log('Message:', error.message);

                alert('An error occurred while fetching notifications.');
            });
        },

        async markAllAsRead() {
            try {
                const res = await api.post('/notifications/all-read');
                this.notifications.forEach(notification => {
                    notification.read_at = new Date().toISOString();
                });
                this.unreadNotifications = 0;
                toast.success(res.data.message);

            } catch (error) {
                console.error(error);
                toast.error("An error occurred while marking notifications as read.");
            }
        },

        async deleteNotification(notification) {
            try {
                const res = await api.delete(`/notifications/${notification.id}`);

                // filter katdor 3la tableau notifications w katkhli ghir li chert dyalhom s7i7
                this.notifications = this.notifications.filter(notif => notif.id !== notification.id);

                if (!notification.read_at) {
                    this.unreadNotifications = Math.max(0, this.unreadNotifications - 1);
                }

                toast.success(res.data.message);

            } catch (error) {
                console.error(error);
                toast.error("An error occurred while deleting the notification.");
            }
        },

        async openNotification(notification) {

            const projectId = notification.data.project_id;
            const taskId = notification.data.task_id;
            try {
                // 1. Ila notification mazal mat9rat
                if (!notification.read_at) {
                    // Nsifto request l Laravel
                    await api.post(`/notifications/${notification.id}/read`);

                    // Nbdlo l'etat dyal notification
                    notification.read_at = new Date().toISOString();

                    // N9sso compteur b wa7ed
                    this.unreadNotifications = Math.max(0,this.unreadNotifications - 1);
                    //Math.max Kat return akber value mn l 2 values li 3tinaha  : Math.max(2,7) => 7
                }

                // 2. Nsddo dropdown
                const button = this.$refs.notificationButton;

                if (button) {
                    Dropdown.getInstance(button)?.hide();
                }

                if (this.user.role === 'employee' && taskId) {
                    try {
                        await api.get(`/employee/show/tasks/${taskId}`);
                        await this.$router.push({
                            name: 'employee.show.tasks',
                            params: { id: taskId }
                        });
                        return;

                    }catch (error) {
                        if (error.response?.status === 404) {
                            toast.error("This task has been deleted and is no longer available.");
                            return;
                        }
                        throw error; 
                    }
                }
                // 3. N7ello page dyal project
                if (projectId) {
                    
                    try {
                        if ( this.user.role === 'admin') {
                            await api.get(`/projects/${projectId}`);
                        } else if (this.user.role === 'manager') {
                            await api.get(`/manager/projects/${projectId}`);
                        } else if (this.user.role === 'employee') {
                            await api.get(`/employee/projects/${projectId}`);
                        }     
                    } catch (error) {
                        if (error.response?.status === 404) {
                            toast.error("This project has been deleted and is no longer available.");
                            return;
                        }
                        throw error; 
                    }

                    if (this.user.role === 'admin') {
                        await this.$router.push({
                            name: 'admin.show.projects',
                            params: { id: projectId}
                        })
                    }else if (this.user.role === 'manager') {
                        await this.$router.push({
                            name: 'manager.show.projects',
                            params: { id: projectId }
                        });
                    }else if (this.user.role === 'employee') {
                        await this.$router.push({
                            name: 'employee.show.projects',
                            params: { id: projectId }
                        })
                    }
                }
            } catch (error) {
                if (error.response?.status === 404) {
                    toast.error("This project has been deleted and is no longer available.");
                } else {
                    toast.error("An error occurred while opening the notification.");
                }
            }
        },

        capitalize(word){
            if(!word) return '';
            return word.charAt(0).toUpperCase()+word.slice(1);
        },

      logOut() {

        if (!confirm('Are you sure you want to log out?') ) {
          return;
        }

        api.post('/logout')
        .then((res) => {
         
          const data = res.data;
          console.log(data);

          localStorage.removeItem('token');
          localStorage.removeItem('user');
          toast.success('Logged out successfully.');
          this.$router.push('/login');

        })
        .catch((error) => {
          console.log(error.response);
          alert('An error occurred while logging out.');
        });
      }
    }
  }
</script>


<style scoped>

.notification-panel {
    width: 360px;
    max-width: calc(100vw - 30px);
    padding: 0;
    margin-top: 12px;
    border: 1px solid #e9ecef;
    border-radius: 14px;
    overflow: hidden;
    z-index: 1050;
}

.notification-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid #eee;
    background: #fff;
}

.notification-list {
    max-height: 350px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    width: 100%;
    padding: 15px 16px;
    border: 0;
    border-bottom: 1px solid #f1f1f1;
    background: #fff;
    text-align: left;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background: #f7f9fc;
}

.notification-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #eaf1ff;
    color: #315fba;
    font-size: 19px;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    font-size: 14px;
    color: #212529;
    margin-bottom: 5px;
}

.notification-message {
    font-size: 13px;
    color: #6c757d;
    line-height: 1.5;
    margin: 0 0 6px;
    overflow-wrap: anywhere;
}

.notification-dot {
    width: 8px;
    height: 8px;
    margin-top: 6px;
    border-radius: 50%;
    background: #0d6efd;
    flex-shrink: 0;
}

.notification-empty {
    padding: 30px 15px;
    text-align: center;
    color: #6c757d;
    background: #fff;
}

@media (max-width: 576px) {
    .notification-panel {
        position: absolute;
        right: -55px;
        width: calc(100vw - 24px);
        max-width: calc(100vw - 24px);
    }
}


.navbar-custom{

    background:linear-gradient(90deg,#1d2738,#111827);

    box-shadow:0 8px 20px rgba(0,0,0,.25);

}

/* Search */

.search-input{

    width:250px;

    border:none;

    border-radius:30px;

    background:rgba(255,255,255,.08);

    color:white;

    padding-left:45px;

    transition:.3s;

}

.search-input::placeholder{

    color:#c8d0da;

}

.search-input:focus{

    background:rgba(255,255,255,.15);

    color:white;

    box-shadow:none;

}

.search-icon{

    left:16px;

    top:50%;

    transform:translateY(-50%);

    color:#d4d8df;

}

/* Notification */

.notification-btn{

    width:46px;

    height:46px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    border:none;

    color:white;

    transition:.3s;

}

.notification-btn:hover{

    background:#3b82f6;

}

/* User */

.user-btn{

    background:rgba(255,255,255,.08);

    color:white;

    border:none;

    transition:.3s;

}

.user-btn:hover{

    background:#3b82f6;

    color:white;

}

.avatar{

    width:42px;

    height:42px;

    border-radius:50%;

    background:#3b82f6;

    color:white;

    display:flex;

    justify-content:center;

    align-items:center;

    font-weight:bold;

}

.user-btn small{

    color:#c8d0da;

}

/* Dropdown */

.dropdown-menu{

    background:#1d2738;

    border:none;

}

.dropdown-item{

    color:white;

}

.dropdown-item:hover{

    background:#273449;

}

</style>
