<template>
    <div class="comp">
        <h1>This is a Test Component</h1>
        <h2>The character {{ props.characterName }} is {{  props.characterAge }} years old, and he is {{ props.characterPerson.name }}</h2>
        <h2>he is {{ props.characterReality ? 'evil' : 'not evil' }}, and his power is {{ props.characterPower[0] }}</h2>
        <h4>he can transform to : </h4>
        <ul>
            <li v-for="(transform, i) in props.characterPower.slice(1)" :key="i" >
                {{ transform }}
            </li>
        </ul>
    </div>
</template>

<script setup>
// error hadi import { defineProps } from 'vue';

const props = defineProps({
    characterName: {
        type: String,
        required: true,
    },
    characterAge: {
        type: Number,
        required: true,
    },
    characterPerson: {
        type: Object,
        required: true,
    },
    characterReality: {
        type: Boolean,
        required: true,
    },
    characterPower: {
        type : Array,
        required: true,
    }
});
</script>