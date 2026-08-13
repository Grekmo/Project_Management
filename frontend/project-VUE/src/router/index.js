import { createRouter, createWebHistory } from 'vue-router'
//import HomeView from '@/views/HomeView.vue'
//const about = () => import('@/views/AboutView.vue')
//const Login = () => import('@/views/LoginView.vue')

const MainLayout = () => import('@/layouts/MainLayout.vue')
//const AdminDashboard = () => import('@/views/admin/Dashboard.vue')
//const AdminEmployees = () => import('@/views/admin/Employees.vue')
//const AdminProjects = () => import('@/views/admin/Projects.vue')
//const AdminTasks = () => import('@/views/admin/Tasks.vue')


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin',
      component: MainLayout,
      meta: { role: 'admin' },
      children: [
        { path: 'dashboard', name: 'admin.dashboard', component: () => import('@/views/admin/Dashboard.vue') },
        //  ************* PROJECTS
        { path: 'projects', name: 'admin.projects', component:() => import('@/views/admin/Projects/Index.vue') },
        { path: 'projects/create', name: 'admin.create.projects', component:() => import('@/views/admin/Projects/Create.vue') },
        { path: 'projects/:id/edit', name: 'admin.edit.projects', component:() => import('@/views/admin/Projects/Edit.vue') },
        { path: 'projects/:id/show', name: 'admin.show.projects', component:() => import('@/views/admin/Projects/Show.vue') },
        // ************** USERS
        { path: 'users', name: 'admin.users', component: () => import('@/views/admin/Users/Index.vue')},
        { path: 'users/create', name: 'admin.create.users', component:() => import('@/views/admin/Users/Create.vue') },
        { path: 'users/:id/edit', name: 'admin.edit.users', component:() => import('@/views/admin/Users/Edit.vue') },
        { path: 'users/:id/show', name: 'admin.show.users', component:() => import('@/views/admin/Users/Show.vue') },
        // *************  TASKS
        { path: 'tasks', name: 'admin.tasks', component: ()=> import('@/views/admin/Tasks/Index.vue')},
        { path: 'tasks/create', name: 'admin.create.tasks', component:() => import('@/views/admin/Tasks/Create.vue') },
        { path: 'tasks/:id/edit', name: 'admin.edit.tasks', component:() => import('@/views/admin/Tasks/Edit.vue') },
        { path: 'tasks/:id/show', name: 'admin.show.tasks', component:() => import('@/views/admin/Tasks/Show.vue') },

      ],
    },
    {
      path: '/manager',
      component: MainLayout,
      meta: { role : 'manager'},
      children: [
        { path: 'dashboard', name: 'manager.dashboard', component: ()=> import('@/views/manager/Dashboard.vue') },
        //  ************* PROJECTS
        { path: 'projects', name: 'manager.myProjects', component:() => import('@/views/manager/Projects/Index.vue'), meta: { title: 'My Projects' }},
        { path: 'projects/:id/edit', name: 'manager.edit.projects', component:() => import('@/views/manager/Projects/Edit.vue') },
        { path: 'projects/:id/show', name: 'manager.show.projects', component:() => import('@/views/manager/Projects/Show.vue') },
        // *************  TASKS
        { path: 'tasks', name: 'manager.tasks', component: ()=> import('@/views/manager/Tasks/Index.vue') , meta: { title: 'My Tasks' } },
        { path: 'tasks/create', name: 'manager.create.tasks', component:() => import('@/views/manager/Tasks/Create.vue') },
        { path: 'tasks/:id/edit', name: 'manager.edit.tasks', component:() => import('@/views/manager/Tasks/Edit.vue') },
        { path: 'tasks/:id/show', name: 'manager.show.tasks', component:() => import('@/views/manager/Tasks/Show.vue') },
        // *************  USERS
        { path: 'team', name: 'manager.team', component: ()=> import('@/views/manager/Users/Index.vue') , meta: { title: 'My Team' } },
        { path: 'user/:id/show', name: 'manager.show.users', component:() => import('@/views/manager/Users/Show.vue') },
      ],
    },
    {
      path: '/employee',
      component: MainLayout,
      meta: { role : 'employee'},
      children: [
        { path: 'dashboard', name: 'employee.dashboard', component: ()=> import('@/views/employee/Dashboard.vue') },
       
        //  ************* PROJECTS
        { path: 'projects', name: 'employee.myProjects', component: ()=> import('@/views/employee/Projects/Index.vue') , meta: { title: 'My Projects' } },
        { path: 'projects/:id/show', name: 'employee.show.projects', component: ()=> import('@/views/employee/Projects/Show.vue') },

        //**************  TASKS
        { path: 'tasks', name: 'employee.myTasks', component: ()=> import('@/views/employee/Tasks/Index.vue') , meta: { title: 'My Tasks' } },
        { path: 'tasks/:id/show', name: 'employee.show.tasks', component: ()=> import('@/views/employee/Tasks/Show.vue') },
        { path: 'tasks/:id/edit', name: 'employee.edit.tasks', component: ()=> import('@/views/employee/Tasks/Edit.vue') },

        // *************  USERS
        { path: 'teamMember', name: 'employee.teamMember', component: ()=> import('@/views/employee/Users/Index.vue') , meta: { title: 'My Team Memebers' } },
        { path: 'member/:id/show', name: 'employee.show.users', component: ()=> import('@/views/employee/Users/Show.vue') },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/auth/Login.vue'),
      meta: { title:'Login Page' }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/auth/Register.vue'),
      meta: { title:'Register Page' }
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('@/views/auth/Logout.vue'),
      meta: { title:'Logout Page' }
    },
    //   *********** TEST ROUTES ************* 
    {
      path: '/thelogin',
      name: 'thelogin',
      component: () => import('@/views/LoginView.vue'),
      meta: { title:'Login Page' }
    },
  
    {
      path: '/testlogin',
      name: 'testlogin',
      component: () => import('@/views/TestLoginView.vue'),
    },
    {
      path: '/testhome',
      name: 'testhome',
      component: () => import('@/views/TestHomeView.vue'),
      meta: { title: 'Home Page' }
    },
    {
      path: '/testabout',
      name: 'testabout',
      component: () => import('@/views/AboutView.vue'),
      meta: { title: 'About Page' }
    }
  ],
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  next();
})

export default router
