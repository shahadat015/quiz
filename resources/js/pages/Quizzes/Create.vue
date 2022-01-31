<template>
	<main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Create Question
            </h2>
            <app-notification v-if="success" :message="message"></app-notification>
            <div class="p-6 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <form @submit.prevent="createQuestion" class="space-y-4">
                    <div class="sm:flex space-y-4 sm:space-y-0 sm:space-x-6">
                        <div class="sm:w-1/2 space-y-4">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Question</span>
                                <app-input
                                    :value="form.question"
                                    @inputEvent="form.question = $event"
                                    placeholder="Enter your question"
                                />
                                <app-error :errors="errors" field="question"></app-error>
                            </label>
                            
                            <label class="block text-sm" v-for="(option, i) in form.options" :key="option">
                                <span class="text-gray-700 dark:text-gray-400">Option {{ i + 1 }}</span>
                                <app-textarea
                                    :value="option.answer"
                                    @inputEvent="option.answer = $event"
                                    placeholder="Enter your question"
                                ></app-textarea>
                                <div class="mt-2 text-white">
                                    <input type="radio" v-model="form.correct_answer" :value="i"/> <label for="">Mark as correct answer</label>
                                </div>
                            </label>
                            <app-button @click.prevent="addOption">Add Option</app-button>
                            
                        </div>
                        <div class="sm:w-1/2 space-y-4">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Note</span>
                                <app-textarea
                                    :value="form.note"
                                    @inputEvent="form.note = $event"
                                    placeholder="Enter Question Note"
                                />
                                <small class="dark:text-gray-400 text-gray-700">If you have any specific instruction.</small>
                                <app-error :errors="errors" field="note"></app-error>
                            </label>
                            <app-button classes="w-full" :disabled="loading">Submit</app-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>
<script>
    import AppButton from '../../components/Form/Button.vue';
    import AppNotification from '../../components/Notification.vue';
    import AppInput from '../../components/Form/Input.vue'
    import AppTextarea from '../../components/Form/Textarea.vue';
    import AppError from '../../components/ValidationError.vue';
    import { mapActions, mapGetters } from 'vuex'

    export default {
        components: {
            AppButton,
            AppTextarea,
            AppInput,
            AppError,
            AppNotification
        },

        data() {
            return {
                form: {
                    question: '',
                    options: [
                        { answer: '' },
                        { answer: '' },
                    ],
                    correct_answer: '',
                    note: '',
                },
                errors: {},
                message: '',
                success: false,
                loading: false
            }
        },

        computed: {
            ...mapGetters({
                user: 'auth/user'
            }),
        },

        methods: {
            ...mapActions({
                store: 'store'
            }),

            async createQuestion() {
                try {
                    this.loading = true;
                    let { data } = await axios.post('/api/quizzes', this.form);
                    this.loading = false;
                    Object.assign(this.$data, this.$options.data());
                    this.message = data.message;
                    this.success = true;
                } catch (error){
                    this.errors = error.response.data.errors;
                    this.loading = false;
                    this.$notify({ text: error.response.data.message, type: 'error' });
                }
            },

            addOption(){
                this.form.options.push({
                    answer: ''
                });
            }
        },
    }
</script>