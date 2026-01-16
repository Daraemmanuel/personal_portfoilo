<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { MessageCircle, Send } from 'lucide-vue-next';

const props = defineProps<{
    articleId: number;
    comments?: Array<{
        id: number;
        author_name: string;
        content: string;
        created_at: string;
        replies?: Array<{
            id: number;
            author_name: string;
            content: string;
            created_at: string;
        }>;
    }>;
}>();

const { success, error } = useToast();
const showForm = ref(false);
const replyingTo = ref<number | null>(null);

const form = useForm({
    author_name: '',
    author_email: '',
    content: '',
    parent_id: null as number | null,
});

const submit = () => {
    form.post(route('comments.store', props.articleId), {
        preserveScroll: true,
        onSuccess: () => {
            success('Thanks! Your comment is pending review.');
            form.reset();
            showForm.value = false;
            replyingTo.value = null;
        },
        onError: () => {
            error('Unable to submit comment. Please try again.');
        },
    });
};

const startReply = (commentId: number) => {
    replyingTo.value = commentId;
    form.parent_id = commentId;
    showForm.value = true;
};
</script>

<template>
    <section class="mt-16 border-t border-white/10 pt-16">
        <div class="mb-8 flex items-center gap-3">
            <MessageCircle class="h-6 w-6 text-indigo-400" />
            <h2 class="text-2xl font-semibold text-white">
                Comments
                <span v-if="comments" class="text-zinc-500">({{ comments.length }})</span>
            </h2>
        </div>

        <!-- Comment Form -->
        <div
            v-if="showForm"
            class="mb-8 rounded-2xl border border-white/10 bg-zinc-900/50 p-6"
        >
            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-300">
                            Name
                        </label>
                        <input
                            v-model="form.author_name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-white placeholder-zinc-600 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            placeholder="Your name"
                        />
                        <InputError :message="form.errors.author_name" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-300">
                            Email
                        </label>
                        <input
                            v-model="form.author_email"
                            type="email"
                            required
                            class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-white placeholder-zinc-600 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            placeholder="your@email.com"
                        />
                        <InputError :message="form.errors.author_email" />
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-300">
                        Comment
                    </label>
                    <textarea
                        v-model="form.content"
                        rows="4"
                        required
                        class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-white placeholder-zinc-600 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        placeholder="Write your comment..."
                    ></textarea>
                    <InputError :message="form.errors.content" />
                    <!-- Honeypot -->
                    <input
                        type="text"
                        name="website"
                        tabindex="-1"
                        autocomplete="off"
                        class="hidden"
                    />
                </div>
                <div class="flex items-center gap-3">
                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-indigo-600 hover:bg-indigo-700"
                    >
                        <Send class="mr-2 h-4 w-4" />
                        {{ replyingTo ? 'Reply' : 'Post Comment' }}
                    </Button>
                    <Button
                        type="button"
                        variant="ghost"
                        @click="showForm = false; replyingTo = null; form.reset()"
                        class="text-zinc-400"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
        </div>

        <Button
            v-else
            @click="showForm = true"
            variant="outline"
            class="mb-8"
        >
            <MessageCircle class="mr-2 h-4 w-4" />
            Add a Comment
        </Button>

        <!-- Comments List -->
        <div v-if="comments && comments.length > 0" class="space-y-6">
            <article
                v-for="comment in comments"
                :key="comment.id"
                class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-white">{{ comment.author_name }}</h4>
                        <p class="text-sm text-zinc-500">
                            {{ new Date(comment.created_at).toLocaleDateString() }}
                        </p>
                    </div>
                    <Button
                        variant="ghost"
                        size="sm"
                        @click="startReply(comment.id)"
                        class="text-xs text-zinc-400"
                    >
                        Reply
                    </Button>
                </div>
                <p class="text-zinc-300">{{ comment.content }}</p>

                <!-- Replies -->
                <div
                    v-if="comment.replies && comment.replies.length > 0"
                    class="mt-6 ml-8 space-y-4 border-l-2 border-white/10 pl-6"
                >
                    <article
                        v-for="reply in comment.replies"
                        :key="reply.id"
                        class="rounded-lg border border-white/5 bg-zinc-900/50 p-4"
                    >
                        <div class="mb-2 flex items-center justify-between">
                            <h5 class="font-semibold text-white">{{ reply.author_name }}</h5>
                            <p class="text-xs text-zinc-500">
                                {{ new Date(reply.created_at).toLocaleDateString() }}
                            </p>
                        </div>
                        <p class="text-sm text-zinc-300">{{ reply.content }}</p>
                    </article>
                </div>
            </article>
        </div>

        <div v-else class="text-center text-zinc-500">
            <MessageCircle class="mx-auto mb-4 h-12 w-12 opacity-50" />
            <p>No comments yet. Be the first to comment!</p>
        </div>
    </section>
</template>

