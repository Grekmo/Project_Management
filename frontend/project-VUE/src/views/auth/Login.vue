<template>
    <div class="min-vh-100 d-flex justify-content-center align-items-center bg-light">
        <div class="col-lg-5 col-md-7">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <i class="bi bi-person-circle display-4"></i>
                    <h2 class="fw-bold mt-2 mb-1">
                        Welcome Back
                    </h2>
                    <p class="mb-0 opacity-75">
                        Sign in to continue
                    </p>
                </div>
                <div class="card-body p-4">
                    <form @submit.prevent="handleLogin">

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                Email Address
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control form-control-lg"
                                placeholder="example@gmail.com"
                            >
                            <small
                                class="text-danger"
                                v-if="errorList.email"
                            >
                                {{ errorList.email[0] }}
                            </small>
                        </div>

                        <!-- Password -->

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill text-primary me-2"></i>
                                Password
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                class="form-control form-control-lg"
                                placeholder="Enter your password"
                            >
                            <small
                                class="text-danger"
                                v-if="errorList.password"
                            >
                                {{ errorList.password[0] }}
                            </small>
                        </div>

                        <!-- Remember -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input
                                    v-model="form.remember"
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember"
                                >
                                <label
                                    class="form-check-label"
                                    for="remember"
                                >
                                    Remember me
                                </label>
                            </div>
                        </div>
                        <div
                            class="alert alert-danger"
                            v-if="error"
                        >
                            {{ error }}
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-3 fw-bold"
                        >
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </button>
                    </form>
                </div>

                <div class="card-footer bg-white text-center border-0 py-4">

                    <span class="text-muted">
                        Don't have an account?
                    </span>

                    <RouterLink
                        to="/Register"
                        class="fw-bold text-decoration-none ms-2"
                    >
                        Sign Up
                    </RouterLink>
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
    data() {
      return {
        errorList: {},
        error: '',
        form: {
          email:'',
          password:'',
          remember: false,
        }
      }
    },

    mounted(){
    },

    methods: {

      handleLogin() {

        this.errorList = {};
        this.error= '';

        api.post('/login', this.form)
        .then((res) => {

          const data = res.data;
          const user = data.user; 
          
          //Enregister les informations dyal user w token . f localStorage kat9bel ghir type string w user 3endna object
          localStorage.setItem('token', data.token);
          localStorage.setItem('user', JSON.stringify(user));

          toast.success(res.data.message);
          console.log(data);

          if (user.role === 'admin') {

            this.$router.push( {name: 'admin.dashboard'} );

          }else if (user.role === 'manager') {

            this.$router.push( {name: 'manager.dashboard'} );

          }else if (user.role === 'employee') { 

            this.$router.push( {name: 'employee.dashboard'} );
          }

          //console.log('success');
        })
        .catch((error)=>{

          if (error.response.status === 401) {

            toast.error(error.response.data.message);

          }else if (error.response.status === 422) {

            this.errorList = error.response.data.errors;
          } 
          //this.errorList = error.response.data.errors;
          //this.error = error.response.data.message;
        });
      }
    }
  }
  /**error
          │
          └── response
                │
                └── data
                      │
                      ├── message
                      └── errors
                            │
                            ├── email
                            └── password */
  /*import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import { useAuthStore } from '@/stores/auth';

  const router = useRouter();
  const auth = useAuthStore();

  const email = ref('');
  const password = ref('');
  const remember = ref(false);

  const handleLogin = () => {
    const fakeUser = {
      name : 'mouad',
      email :  email.value,
      role : 'admin',
    }
    auth.Login(fakeUser);

    if (fakeUser.role === 'admin') {
      router.push('/admin/dashboard');
    } else if (fakeUser.role === 'manager') {
      router.push('/manager/dashboard');
    } else {
      router.push('/employee/dashboard');
    }
  }*/
</script>

<style scoped>
  .card{
      animation: fadeIn .35s ease;
  }

  .form-control{
      border-radius:12px;
  }

  .form-control:focus{
      box-shadow:0 0 0 .2rem rgba(13,110,253,.15);
  }

  .btn{
      border-radius:12px;
  }

  @keyframes fadeIn{
      from{
          opacity:0;
          transform:translateY(20px);
      }
      to{
          opacity:1;
          transform:translateY(0);
      }
  }

</style>