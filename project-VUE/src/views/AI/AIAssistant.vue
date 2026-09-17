<template>
    <div class="ai-page">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-robot me-2"></i>
                AI Assistant
            </h2>

            <p class="text-muted mb-0">
                Ask the AI about your project and its tasks.
            </p>
        </div>

        <div class="row g-4">

            <!-- Conversations -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-chat-dots me-2"></i>
                                Conversations
                            </h5>

                            <button
                                class="btn btn-primary btn-sm"
                                @click="newConversation"
                            >
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div
                        v-for="conversation in conversations"
                        :key="conversation.id"
                        class="conversation-item"
                        :class="{ 'conversation-active': conversation.id === conversation_id }"
                    >
                        <template v-if="editingConversationId === conversation.id">

                            <input
                                v-model="editingTitle"
                                class="conversation-rename-input"
                                @keyup.enter="renameConversation(conversation.id)"
                                @keyup.esc="cancelRename"
                                @click.stop
                            >

                            <button
                                class="conversation-action"
                                @click.stop="renameConversation(conversation.id)"
                                title="Save"
                            >
                                <i class="bi bi-check-lg"></i>
                            </button>

                            <button
                                class="conversation-delete"
                                @click.stop="cancelRename"
                                title="Cancel"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>

                        </template>

                        <template v-else>

                            <button
                                class="conversation-title"
                                @click="selectConversation(conversation.id)"
                            >
                                <i class="bi bi-chat-left-text me-2"></i>
                                <span>{{ conversation.title }}</span>
                            </button>

                            <button
                                class="conversation-action"
                                @click.stop="startRename(conversation)"
                                title="Rename conversation"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button
                                class="conversation-delete"
                                @click.stop="deleteConversation(conversation.id)"
                                title="Delete conversation"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>

                        </template>
                    </div>
                </div>
            </div>

            <!-- Chat -->
            <div class="col-md-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-chat-dots me-2"></i>
                            Conversation
                        </h5>
                    </div>

                    <!-- Project -->
                    <div class="card-body border-bottom p-4">
                        <label class="form-label fw-semibold">
                            Select a project
                        </label>

                        <select
                            v-model="selectedProject"
                            class="form-select"
                        >
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


                    <!-- Messages -->
                    <div class="chat-container p-4">
                        <div
                            v-if="messages.length === 0 && !loading"
                            class="empty-chat text-center text-muted"
                        >
                            <i class="bi bi-robot fs-1 d-block mb-3"></i>
                            <p class="mb-0">
                                Select a project and ask the AI something about it.
                            </p>
                        </div>

                        <div
                            v-for="(msg, index) in messages"
                            :key="index"
                            class="message-wrapper mb-3"
                            :class="msg.role === 'user' ? 'user-message' : 'ai-message'"
                        >
                            <div class="message">
                                <div class="message-header mb-1">
                                    <strong>
                                        {{ msg.role === 'user'
                                            ? 'You'
                                            : 'AI Assistant'
                                        }}
                                    </strong>
                                </div>
                                <div class="message-text">
                                    {{ msg.content }}
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="loading"
                            class="ai-message mb-3"
                        >
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


                    <!-- Input -->
                    <div class="card-footer bg-white border-0 p-4">
                        <div class="input-group">
                            <input
                                v-model="message"
                                type="text"
                                class="form-control"
                                placeholder="Ask something about this project..."
                                @keyup.enter="sendMessage"
                            />

                            <button
                                class="btn btn-primary px-4"
                                @click="sendMessage"
                                :disabled="loading || !message.trim()"
                            >
                                <i class="bi bi-send me-2"></i>
                                Send
                            </button>
                        </div>
                    </div>
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
                projects: [],
                conversations: [],
                conversation_id: null,
                selectedProject: null,
                message: '',
                messages: [],
                loading: false,
                editingConversationId: null,
                editingTitle: '',
            }
        },


        mounted() {
            this.loadProjects();
            this.loadConversations();
        },


        methods: {

            startRename(conversation) {
                this.editingConversationId = conversation.id;
                this.editingTitle = conversation.title;
            },

            cancelRename() {
                this.editingConversationId = null;
                this.editingTitle = '';
            },
            async renameConversation(id, title) {
                if (!this.editingTitle.trim()) {
                    return;
                }
                try {
                    const response = await api.put(`/ai/conversation/${id}`, { title: this.editingTitle.trim() });
                    console.log(response.data.message);
                    this.cancelRename();
                    await this.loadConversations();
                }catch (error) {
                    console.error('Error renaming conversation:', error);
                }
            },

            async loadProjects() {
                try {
                    const response = await api.get('/projects');
                    this.projects = response.data.projects;
                } catch (error) {
                    console.error(
                        'Error loading projects:',
                        error
                    );
                }
            },

            async loadConversations() {
                try {
                    const response = await api.get('/ai/conversations');
                    this.conversations = response.data.conversations;
                } catch (error) {
                    console.error(
                        'Error loading conversations:',
                        error
                    );
                }
            },

            newConversation() {
                this.conversation_id = null;
                this.messages = [];
                this.message = '';
            },

            async selectConversation(id) {
                console.log('Selected conversation:', id);

                try {
                    const response = await api.get(`/ai/conversation/${id}`);
                    
                    console.log('Conversation response:', response.data);
                    this.conversation_id = response.data.conversation.id;
                    this.messages = response.data.messages.map(mssg => ({
                        role : mssg.sender,
                        content : mssg.message,
                    }))
                }catch (error) {
                    console.error('Error loading conversation:', error);
                }
            },

            async sendMessage() {

                if (!this.message.trim()) {
                    return;
                }

                const userMessage = this.message.trim();

                this.messages.push({
                    role: 'user',
                    content: userMessage
                });

                this.message = '';
                this.loading = true;

                try {
                    const data = {
                        message: userMessage
                    };
                    if (this.selectedProject) {
                        data.project_id = this.selectedProject;
                    }
                    if (this.conversation_id) {
                        data.conversation_id = this.conversation_id;
                    }

                    const response = await api.post('/ai/ask', data );
                    
                    this.conversation_id = response.data.conversation_id;
                    this.messages.push({
                        role: 'ai',
                        content: response.data.response
                    });
                    // Refresh conversations
                    await this.loadConversations();

                } catch (error) {
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
            },

            async deleteConversation(id) {
                try {
                    const response = await api.delete(`/ai/conversation/${id}`);
                    console.log(response.data.message);
                    this.newConversation();
                    this.loadConversations();
                }catch (error) {
                    console.error(
                        'Error deleting conversation:',
                        error
                    );
                }
            }
        }
    }
</script>

<style>
    .conversation-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 4px 10px;
    padding: 8px 10px;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.conversation-item:hover {
    background: #f5f7fa;
}

.conversation-active {
    background: #eef4ff;
}

.conversation-title {
    flex: 1;
    border: 0;
    background: transparent;
    text-align: left;
    padding: 6px 4px;
    color: #343a40;
    font-weight: 500;
}

.conversation-active .conversation-title {
    color: #0d6efd;
    font-weight: 600;
}

.conversation-delete {
    border: 0;
    background: transparent;
    color: #adb5bd;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.conversation-delete:hover {
    color: #dc3545;
    background: #fff0f0;
}

.conversation-action {
    border: 0;
    background: transparent;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: #adb5bd;
    transition: all 0.2s ease;
}

.conversation-action:hover {
    color: #0d6efd;
    background: #eef4ff;
}
.message-wrapper {
    display: flex;
}

.user-message {
    justify-content: flex-end;
}

.ai-message {
    justify-content: flex-start;
}

.user-message .message {
    text-align: right;
}

.ai-message .message {
    text-align: left;
}

.message {
    max-width: 70%;
}

.chat-container {
    height: 500px;
    overflow-y: auto;
}
</style>