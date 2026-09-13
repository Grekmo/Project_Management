<template>
    <div class="ai-page">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-robot me-2"></i>
                AI Assistant
            </h2>

            <p class="text-muted mb-0">
                Ask the AI about your project and its tasks.
            </p>
        </div>

        <!-- Project Selection -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <label class="form-label fw-semibold">
                    Select a project
                </label>

                <select v-model="selectedProject" class="form-select">
                    <option :value="null">
                        All Projects
                    </option>

                    <option
                        v-for="project in projects"
                        :key="project.id"
                        :value="project.id"
                    >
                        {{ project.name }}
                    </option>
                </select>

            </div>
        </div>


        <!-- Chat -->
        <div class="card border-0 shadow-sm rounded-4">

            <!-- Chat Header -->
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-chat-dots me-2"></i>
                    Conversation
                </h5>
            </div>


            <!-- Messages -->
            <div class="chat-container p-4">
                <!-- Empty state -->
                <div
                    v-if="messages.length === 0 && !loading"
                    class="empty-chat text-center text-muted"
                >
                    <i class="bi bi-robot fs-1 d-block mb-3"></i>
                    <p class="mb-0">
                        Select a project and ask the AI something about it.
                    </p>
                </div>


                <!-- Messages -->
                <div v-for="(message, index) in messages":key="index" class="message-wrapper mb-3"
                    :class="message.role === 'user' ? 'user-message' : 'ai-message'">
                    <div class="message">
                        <div class="message-header mb-1">
                            <strong>
                                {{ message.role === 'user' ? 'You' : 'AI Assistant'}}
                            </strong>
                        </div>

                        <div class="message-text">
                            {{ message.content }}
                        </div>
                    </div>
                </div>


                <!-- Loading -->
                <div  v-if="loading" class="ai-message mb-3">
                    <div class="message">
                        <strong>AI Assistant</strong>
                        <div class="mt-2">
                            <span
                                class="spinner-border spinner-border-sm me-2"
                            ></span>
                            Thinking...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="card-footer bg-white border-0 p-4">
                <div class="input-group">
                    <input
                        v-model="message"
                        type="text"
                        class="form-control"
                        placeholder="Ask something about this project..."
                        @keyup.enter="sendMessage"
                    />

                    <button class="btn btn-primary px-4" @click="sendMessage" :disabled="loading || !message.trim()">
                        <i class="bi bi-send me-2"></i>
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import api from '@/services/axios.js';
    export default {
        data() {
            return {
                // Projects displayed in the select
                projects: [],
                selectedProject: null,
                message: '',
                // Chat messages
                messages: [],
                // AI loading state
                loading: false
            }
        },

        mounted() {
            this.loadProjects();
        },

        methods: {

            // Get projects for the current user
            // await is used to wait for the API response before continuing
            // await must be used inside an async function 
            async loadProjects() {
                try {
                    const response = await api.get('/projects');
                    console.log('PROJECTS RESPONSE:', response.data);
                    this.projects = response.data.projects;
                } catch (error) {
                    console.error(
                        'Error loading projects:',
                        error
                    );
                }
            },

            // Send message to AI
            async sendMessage() {

                if(!this.message.trim() )//trim() bach y7iyed les espaces avant et apres le message, donc si message vide, return
                {
                    return;
                }
                const userMessage = this.message.trim();
                // Add user's message to chat
                this.messages.push({
                    role: 'user',
                    content: userMessage
                });
                // Clear input
                this.message = '';
                // Start loading
                this.loading = true;

                try {
                    const data = {
                        message: userMessage
                    };

                    if (this.selectedProject) {
                        data.project_id = this.selectedProject;
                    }

                    const response = await api.post('/ai/ask', data);
                    this.messages.push({
                        role: 'ai',
                        content: response.data.response
                    });

                } catch (error) 
                {
                    console.error(
                        'AI Error:',
                        error.response?.data || error
                    );
                    this.messages.push({
                        role: 'ai',
                        content: 'Sorry, something went wrong while contacting the AI.'
                    });
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>


<style scoped>

.ai-page {
    min-height: calc(100vh - 100px);
}


/* Chat */

.chat-container {
    min-height: 400px;
    max-height: 500px;
    overflow-y: auto;
    background: #f8fafc;
}


/* Empty chat */

.empty-chat {
    padding-top: 100px;
}


/* Messages */

.message-wrapper {
    display: flex;
}

.user-message {
    justify-content: flex-end;
}

.ai-message {
    justify-content: flex-start;
}


.message {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 16px;
}


.user-message .message {
    background: #3b82f6;
    color: white;
    border-bottom-right-radius: 4px;
}


.ai-message .message {
    background: white;
    color: #1f2937;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}


/* Message text */

.message-header {
    font-size: 13px;
}

.message-text {
    white-space: pre-wrap;
    line-height: 1.6;
}


/* Inputs */

.form-control,
.form-select {
    border-radius: 10px;
    padding: 10px 14px;
}


.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}


.input-group .btn {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

</style>