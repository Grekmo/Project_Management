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
            <button class="btn notification-btn position-relative">
                <i class="bi bi-bell fs-5"></i>
                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                >
                    3
                </span>
            </button>
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
        }
      }
    },

    mounted() {
      this.user = JSON.parse(localStorage.getItem('user')); // parse kan7owl string l object bach n9dro nst3mlo f vue
      this.token = localStorage.getItem('token');
      console.log('User data from localStorage:', this.user);
      console.log('Token from localStorage:', this.token);
    },

    methods: {

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
