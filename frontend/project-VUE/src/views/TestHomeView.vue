<template>
  <div class="home">
    <!--
      <main>
        <MainLayout /> 
      </main> 
    -->
      <main>
        <MainLayout /> 
      </main> 
    <button @click="prenom = 'Giyu'">Change Name</button>
    <button @click="tableau[3] = 'Itachi Uchiha'">
      Change Character
    </button>

    <h5>{{ fullName }}</h5>
    
    <input type="text" v-model="name" placeholder="Character name" required="">
    <button @click="addName">Add Character</button>
    <ul>
      <li v-for="(name, i) in names" :key="i">
        {{ name }}
      </li>
    </ul>
      <h1>This is an Home page</h1>
      <TestComponent :characterName="firstName" :CharacterAge="age" :characterPerson="userObject"
        :characterReality="isEvil" :characterPower="arrayBlack"
      />
      <h1>*****************************</h1>
      <h1> {{ welcomeMessage }}</h1>
      <h2>My name is {{ firstName }} </h2>
      <h3>My age is {{ age }}</h3>
      <button @click="incAge">Age Increment</button>
      <h1>{{ userObject.name }} is {{ isEvil ? 'Evil' : 'Not Evil' }}</h1>
      <h2>{{ firstName }} has {{ arrayBlack[0] }} and can turn into {{ arrayBlack[1] }}, {{ arrayBlack[2] }}, {{ arrayBlack[3] }}, and {{ arrayBlack[4] }} </h2>
  </div>
</template>

<script setup>

import MainLayout from '@/layouts/MainLayout.vue';
import TestComponent from '@/components/layout/TestComponent.vue';
import { ref, reactive, computed, onMounted } from 'vue';  
  
const prenom = ref('Mouad');
const nom = ref('Graich');
const tableau = reactive(['Tomiyoka Giyu', 'Gojo Satoru', 'Shinobu Kochou'])

const fullName = computed(() => {
  return `Welcome MR ${prenom.value} ${nom.value}, You have the same character MTBI as ${tableau[0]}, ${tableau[1]}`
})

  //data properties
  const firstName = ref('Black Goku');
  const age = ref(30);
  const userObject = reactive({
    name: 'zamasu',
    age: 30,
    personality: 'evil',
  });
  const isEvil = ref(true);
  const arrayBlack = reactive(['kamehameha','ssj','ssj2','ssj3','ssjrose']);

  const names = reactive(['Goku', 'Vegeta','Broly']);
  const name = ref('');

onMounted(() => {
  console.log(names);
})

  function addName() {
    if (name.value.trim() === '') return;
    names.push(name.value);
    name.value = '';
  }

  function incAge() {
    age.value++;
  }

  //computed properties -
  const welcomeMessage = computed(() => {
    return `welcome ${userObject.name}, you are the same person ${firstName.value}, and You are an ${isEvil.value ? 'Evil' : 'Not Evil'}`;
  })
</script>