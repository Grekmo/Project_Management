<template>
    <aside class="sidebar">
        <!-- Logo -->
        <div class="sidebar-header">
            <div class="logo">
                <i class="bi bi-kanban-fill"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">
                    {{ capitalize(user.role) }}
                </h5>
                <small class="text-light opacity-75">
                    Project Management
                </small>
            </div>
        </div>

        <!-- Menu -->
        <div class="sidebar-menu">
            <router-link
                v-for="(item, index) in menu"
                :key="index"
                :to="{ name: item.routeName }"
                class="menu-item"
                active-class="active-link"
            >
                <i :class="item.icon"></i>
                <span>
                    {{ item.label }}
                </span>
            </router-link>
        </div>

        <!-- User -->
        <div class="sidebar-user">
            <div class="user-card">
                <div class="avatar">
                    {{ user.name?.charAt(0).toUpperCase() }}
                </div>
                <div class="user-info">
                    <h6 class="mb-0">
                        {{ user.name }}
                    </h6>
                    <small>
                        {{ capitalize(user.role) }}
                    </small>
                </div>
            </div>
            <div class="dropdown mt-3"><!--
                <button
                    class="btn btn-sidebar dropdown-toggle w-100"
                    data-bs-toggle="dropdown"
                >
                    <i class="bi bi-gear-fill me-2"></i>
                    Account
                </button>
                
                  <ul class="dropdown-menu dropdown-menu-dark w-100">
                      <li>
                          <a class="dropdown-item" href="#">
                              <i class="bi bi-person me-2"></i>
                              Profile
                          </a>
                      </li>
                      <li>
                          <a class="dropdown-item" href="#">
                              <i class="bi bi-sliders me-2"></i>
                              Settings
                          </a>
                      </li>
                      <li>
                          <hr class="dropdown-divider">
                      </li>
                      <li>
                          <a
                              class="dropdown-item text-danger"
                              href="#"
                          >
                              <i class="bi bi-box-arrow-right me-2"></i>
                              Logout
                          </a>
                      </li>
                  </ul>
                -->
            </div>
        </div>
    </aside>
</template>

<script>

  export default {
    data() {
      return{
        menu: [],
        token: '',
        user: {
          name: '',
          role: ''
        }
      }
    },

    mounted() {
      this.token = localStorage.getItem('token');
      this.user = JSON.parse(localStorage.getItem('user'));
      this.loadMenu();
    },

    methods: {

      capitalize(word) {
        return word.charAt(0).toUpperCase() + word.slice(1);
      },

      loadMenu() {
        if (this.user.role === 'admin') {
          this.menu = [
            { label: 'Dashboard' , icon:'bi bi-speedometer2', routeName: 'admin.dashboard'},
            { label: 'Projects' , icon:'bi bi-folder2-open', routeName: 'admin.projects'},
            { label: 'Tasks' , icon:'bi bi-list-task', routeName: 'admin.tasks'},
            { label: 'Users' , icon:'bi bi-people', routeName: 'admin.users'},
          ]
        } else if (this.user.role === 'manager') {
          this.menu = [
            { label: 'Dashboard' , icon:'bi bi-speedometer2', routeName: 'manager.dashboard'},
            { label: 'My Projects' , icon:'bi bi-folder2-open', routeName: 'manager.myProjects'}, // /*label: 'Projects'*/ deja f INDEX.JS => meta { title: 'myProjects'} donc maghan7tajoch label
            { label: 'Tasks' , icon:'bi bi-list-task', routeName: 'manager.tasks'},
            { label: 'My Team'  , icon:'bi bi-people', routeName: 'manager.team'},
          ]
        } else if (this.user.role === 'employee') {
          this.menu = [
            { label: 'Dashboard', icon:'bi bi-speedometer2', routeName: 'employee.dashboard'},
            { label: 'My Projects' , icon:'bi bi-folder2-open', routeName: 'employee.myProjects'},
            { label: 'My Tasks' , icon:'bi bi-list-task', routeName : 'employee.myTasks'},
            { label: 'Team Members'  , icon:'bi bi-people', routeName: 'employee.teamMember'},
          ]
        }
      }
    }
  }
</script>

<style scoped>

.sidebar{
    width:280px;
    height:100vh;
    background:linear-gradient(180deg,#1d2738,#111827);
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:25px 18px;
    box-shadow:0 15px 40px rgba(0,0,0,.25);
    position:sticky;
    top:0;
}

/* ================= HEADER ================= */

.sidebar-header{

    display:flex;
    align-items:center;
    gap:15px;

    padding-bottom:20px;
    border-bottom:1px solid rgba(255,255,255,.08);

}

.logo{

    width:55px;
    height:55px;

    border-radius:16px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:#3b82f6;

    font-size:26px;

    box-shadow:0 10px 25px rgba(59,130,246,.35);

}

.sidebar-header h5{

    color:white;

}

.sidebar-header small{

    font-size:13px;

}

/* ================= MENU ================= */

.sidebar-menu{

    margin-top:35px;

    display:flex;
    flex-direction:column;

    gap:8px;

}

.menu-item{

    color:#d4d8df;

    text-decoration:none;

    display:flex;

    align-items:center;

    gap:15px;

    padding:14px 18px;

    border-radius:14px;

    transition:.3s;

    font-weight:500;

}

.menu-item i{

    font-size:20px;

}

.menu-item:hover{

    background:#273449;

    color:white;

    transform:translateX(8px);

}

.active-link{

    background:#273449;

    color:white !important;

    border-left:4px solid #3b82f6;

    box-shadow:0 10px 20px rgba(0,0,0,.18);

}

/* ================= USER ================= */

.sidebar-user{

    border-top:1px solid rgba(255,255,255,.08);

    padding-top:25px;

}

.user-card{

    display:flex;

    align-items:center;

    gap:15px;

}

.avatar{

    width:52px;

    height:52px;

    border-radius:50%;

    background:#3b82f6;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:22px;

    font-weight:bold;

    box-shadow:0 8px 20px rgba(59,130,246,.4);

}

.user-info h6{

    color:white;

    margin:0;

}

.user-info small{

    color:#bfc7d4;

}

/* ================= BUTTON ================= */

.btn-sidebar{

    background:#273449;

    color:white;

    border:none;

    border-radius:12px;

    transition:.3s;

}

.btn-sidebar:hover{

    background:#3b82f6;

    color:white;

}

/* ================= DROPDOWN ================= */

.dropdown-menu{

    border:none;

    border-radius:15px;

    overflow:hidden;

}

.dropdown-item{

    padding:10px 15px;

}

.dropdown-item:hover{

    background:#273449;

}

</style>