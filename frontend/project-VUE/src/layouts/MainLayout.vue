<template>
    <div class="main-layout">
        <!-- Navbar -->
        <Navbar />
        <div class="container-fluid">
            <div class="row">
                
                <!-- Sidebar 
                    
                -->
                <Sidebar class="col-2 bg-light min-vh-100" />
                <!-- Page Content -->
                <main class="col p-4">
                    <RouterView />
                </main>
            </div>
        </div>

    </div>
</template>

<script setup>
    import Navbar from '@/components/layout/Navbar.vue'
    import Sidebar from '@/components/layout/Sidebar.vue'
</script>
