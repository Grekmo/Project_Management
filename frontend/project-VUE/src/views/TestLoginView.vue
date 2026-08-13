<template>
  <div class="about">
    <Navbar />
    <h1>This is an Login page</h1>
    <button @click="increment">test ount mounted : {{ count }}</button>
    <div>
      <h2>Login</h2>

      <input v-model="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />

      <button @click="login">Login</button>
      
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/components/layout/Navbar.vue';
import { ref, onMounted} from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');
const password = ref('');
const count = ref(0);

function increment() {
  count.value++;
}

onMounted(() => {
  console.log(`العداد الابتدائي ${count.value}.`)
})

function login() {
  if (email.value === 'admin@gmail.com' && password.value === 'admin123') {
    const user = { 
      name: 'Admin',
      role: 'admin',
    }
    localStorage.setItem('user', JSON.stringify(user))

     router.push('/admin/dashboard')
  } else {
    alert('Invalid credentials')
  }
}
  
</script>
