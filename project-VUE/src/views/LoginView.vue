<template>
    <div class="min-vh-100 bg-light d-flex justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <i class="bi bi-person-circle display-4"></i>
                    <h2 class="fw-bold mt-2 mb-0">
                        Welcome Back
                    </h2>
                    <small>Sign in to your account</small>
                </div>
                <div class="card-body p-4">
                    <form @submit.prevent="loginFunction">
                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                Email
                            </label>
                            <input
                                type="email"
                                class="form-control form-control-lg"
                                placeholder="Enter your email"
                                v-model="form.email"
                            >
                            <small class="text-danger">
                                {{ errorList.email?.[0] }}
                            </small>
                        </div>
                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill text-primary me-2"></i>
                                Password
                            </label>
                            <input
                                type="password"
                                class="form-control form-control-lg"
                                placeholder="Enter your password"
                                v-model="form.password"
                            >
                            <small class="text-danger">
                                {{ errorList.password?.[0] }}
                            </small>
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
            </div>
        </div>
    </div>
</template>
<!-- <template>
  <div>
    <h2>Login</h2>
    <form  @submit.prevent="loginFunction">
      <div>
        <label for="email">Email : </label>
        <input type="text" id="email" v-model="form.email" />
      </div>
      <div>
        <label for="password">Password : </label>
        <input type="password" id="password" v-model="form.password" />
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</template>
-->
<script>
  /**
     1) data()
          ↓
    2) أول render للـ HTML بالقيم الافتراضية
          ↓
    3) mounted()
          ↓
    4) mounted كتنادي methods
          ↓
    5) methods كتجيب البيانات من API
          ↓
    6) كتحدث data()
          ↓
    7) Vue كتعاود render للـ HTML تلقائياً 
  */

  //import api from '@/services/axios.js';
  import axios from 'axios';
  import { useToast } from "vue-toastification";

  const toast = useToast();

  export default {
    data() {
      return{
        errorList: {},
        form : {
          email: '',
          password: '',
        }
      }
    },

    mounted(){
    }, // mounted katnadi awla kat activer methods
    
    methods: {
      
      loginFunction() {

        console.log(this.form);
        axios.post('http://127.0.0.1:8000/api/login', this.form)
        .then((res) => {
           toast.success(res.data.message);
        })
        .catch((error) => {
            toast.error('ERROR');
            console.log(error);
        })
      }
    }
  }
</script>
<style scoped>

  body{
      background:#f5f7fb;
  }

  .card{
      animation:fadeIn .4s ease;
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