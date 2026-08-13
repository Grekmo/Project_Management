<template>
    <div class="about">
        <Navbar />
        <h1>About Page</h1>
        <div v-for="char in characters" :key="char.id">
            <h2>{{ char.name }}'s Power is {{ char.power }}</h2>
            <h2>{{ char.name }}'s Transformations are : </h2>
            <span v-for="(trans, i) in char.transformation" :key="i">
                {{ trans }} / 
            </span>
        </div>
    </div>
    
</template>

<script setup>
import Navbar from '@/components/layout/Navbar.vue';
import { ref } from 'vue';

const characters = ref([
    {
        name: 'Goku',
        id: 1,
        power: 'Kamehameha',
        transformation: ['ssj','ssj2','ssj3','ssjblue','ssjblue kaioken', 'ultra instinct']
    },
    {
        name: 'Vegeta',
        id: 2,
        power: 'Final Flash',
        transformation: ['ssj','ssj2','ssjRed','ssjblue',' ego Vegeta']
    },
    {
        name: 'Gohan',
        id: 3,
        power: 'Masenko',
        transformation: ['ssj','ssj2','ultimate Gohan','beast Gohan']
    }
])
</script>