<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import { useForm } from '@inertiajs/vue3';
import {
    HelpCircle,
    Loader2,
    MessageCircle,
    Send,
    ThumbsUp,
} from 'lucide-vue-next';
import { computed, nextTick, ref } from 'vue';

const props = defineProps<{
    articleId: number;
    comments?: Array<{
        id: number;
        author_name: string;
        content: string;
        created_at: string;
        is_approved?: boolean;
        likes_count?: number;
        helpfuls_count?: number;
        replies?: Array<{
            id: number;
            author_name: string;
            content: string;
            created_at: string;
            is_approved?: boolean;
            likes_count?: number;
            helpfuls_count?: number;
        }>;
    }>;
}>();

const { success, error } = useToast();
const showForm = ref(false);
const replyingTo = ref<number | null>(null);
const replyFormVisible = ref<Record<number, boolean>>({});
const MAX_CHARACTERS = 2000;

// Reaction states
const reactionLoading = ref<Record<number, boolean>>({});
const commentReactions = ref<
    Record<
        number,
        {
            likes: number;
            helpfuls: number;
            userLiked: boolean;
            userHelpful: boolean;
        }
    >
>({});

// Initialize reaction counts from props
props.comments?.forEach((comment) => {
    commentReactions.value[comment.id] = {
        likes: comment.likes_count || 0,
        helpfuls: comment.helpfuls_count || 0,
        userLiked: false,
        userHelpful: false,
    };
    comment.replies?.forEach((reply) => {
        commentReactions.value[reply.id] = {
            likes: reply.likes_count || 0,
            helpfuls: reply.helpfuls_count || 0,
            userLiked: false,
            userHelpful: false,
        };
    });
});

const form = useForm({
    author_name: '',
    content: '',
    parent_id: null as number | null,
});

const characterCount = computed(() => form.content.length);
const isOverLimit = computed(() => characterCount.value > MAX_CHARACTERS);
const remainingCharacters = computed(
    () => MAX_CHARACTERS - characterCount.value,
);

const submit = () => {
    if (isOverLimit.value) {
        error(
            `Comment is too long. Maximum ${MAX_CHARACTERS} characters allowed.`,
        );
        return;
    }

    form.post(route('comments.store', props.articleId), {
        preserveScroll: true,
        onSuccess: () => {
            success('Thanks! Your comment is pending review.');
            form.reset();
            showForm.value = false;
            replyingTo.value = null;
            replyFormVisible.value = {};
        },
        onError: (errors) => {
            if (errors.content) {
                error(
                    Array.isArray(errors.content)
                        ? errors.content[0]
                        : errors.content,
                );
            } else if (errors.author_name) {
                error(
                    Array.isArray(errors.author_name)
                        ? errors.author_name[0]
                        : errors.author_name,
                );
            } else {
                error(
                    'Unable to submit comment. Please check your input and try again.',
                );
            }
        },
    });
};

const startReply = async (commentId: number) => {
    replyFormVisible.value = {};
    replyingTo.value = commentId;
    form.parent_id = commentId;
    replyFormVisible.value[commentId] = true;

    // Auto-focus textarea
    await nextTick();
    setTimeout(() => {
        const textarea = document.querySelector(
            `[data-reply-textarea="${commentId}"]`,
        ) as HTMLTextAreaElement;
        if (textarea) {
            textarea.focus();
            textarea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }, 100);
};

const cancelReply = () => {
    replyingTo.value = null;
    form.parent_id = null;
    form.reset();
    replyFormVisible.value = {};
};

const toggleReaction = async (
    commentId: number,
    reactionType: 'like' | 'helpful',
) => {
    if (reactionLoading.value[commentId]) return;

    reactionLoading.value[commentId] = true;

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(
            route('comments.reactions.store', commentId),
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ reaction_type: reactionType }),
            },
        );

        if (!response.ok) {
            let errorMessage = `HTTP error! status: ${response.status}`;
            try {
                const errorData = await response.json();
                errorMessage =
                    errorData.message || errorData.error || errorMessage;
            } catch {
                // If response is not JSON, use status text
                errorMessage = response.statusText || errorMessage;
            }
            throw new Error(errorMessage);
        }

        const data = await response.json();

        commentReactions.value[commentId] = {
            likes: data.likes_count,
            helpfuls: data.helpfuls_count,
            userLiked: data.user_liked,
            userHelpful: data.user_helpful,
        };
    } catch (err) {
        const errorMessage =
            err instanceof Error
                ? err.message
                : 'Unable to update reaction. Please try again.';
        error(errorMessage);
        console.error('Reaction error:', err);
    } finally {
        reactionLoading.value[commentId] = false;
    }
};
</script>

<template>
    <section class="mt-16 border-t border-border pt-16">
        <div class="mb-8 flex items-center gap-3">
            <MessageCircle
                class="h-6 w-6 text-indigo-500 dark:text-indigo-400"
            />
            <h2 class="text-2xl font-semibold text-foreground">
                Comments
                <span v-if="comments" class="text-muted-foreground"
                    >({{ comments.length }})</span
                >
            </h2>
        </div>

        <!-- Main Comment Form -->
        <div
            v-if="showForm && !replyingTo"
            class="mb-8 rounded-xl border border-border bg-card p-6 shadow-sm"
        >
            <h3 class="mb-4 text-base font-semibold text-foreground">
                Add a Comment
            </h3>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <input
                        v-model="form.author_name"
                        type="text"
                        required
                        autocomplete="name"
                        class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground placeholder-muted-foreground focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                        placeholder="Your name"
                        aria-label="Your name"
                    />
                    <InputError :message="form.errors.author_name" />
                </div>
                <div>
                    <textarea
                        v-model="form.content"
                        rows="4"
                        required
                        :maxlength="MAX_CHARACTERS"
                        class="w-full resize-none rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground placeholder-muted-foreground focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                        :class="
                            isOverLimit
                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                                : ''
                        "
                        placeholder="Share your thoughts..."
                        aria-label="Your comment"
                    ></textarea>
                    <div class="mt-1 flex items-center justify-between">
                        <InputError :message="form.errors.content" />
                        <span
                            class="text-xs"
                            :class="
                                isOverLimit
                                    ? 'font-semibold text-red-500'
                                    : remainingCharacters < 100
                                      ? 'text-yellow-500'
                                      : 'text-muted-foreground'
                            "
                        >
                            {{ characterCount }} / {{ MAX_CHARACTERS }}
                        </span>
                    </div>
                    <!-- Honeypot -->
                    <input
                        type="text"
                        name="website"
                        tabindex="-1"
                        autocomplete="off"
                        class="hidden"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <Button
                        type="submit"
                        :disabled="form.processing || isOverLimit"
                        class="bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                    >
                        <Loader2
                            v-if="form.processing"
                            class="mr-2 h-3 w-3 animate-spin"
                        />
                        <Send v-else class="mr-2 h-3 w-3" />
                        {{ form.processing ? 'Posting...' : 'Post Comment' }}
                    </Button>
                    <Button
                        type="button"
                        variant="ghost"
                        @click="
                            showForm = false;
                            form.reset();
                        "
                        class="px-4 py-2 text-sm text-muted-foreground hover:text-foreground"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
        </div>

        <Button
            v-if="!showForm && !replyingTo"
            @click="showForm = true"
            variant="outline"
            class="mb-8 border-border hover:bg-muted"
        >
            <MessageCircle class="mr-2 h-4 w-4" />
            Add a Comment
        </Button>

        <!-- Comments List -->
        <div v-if="comments && comments.length > 0" class="space-y-6">
            <article
                v-for="comment in comments"
                :key="comment.id"
                class="rounded-2xl border border-border bg-card p-6 shadow-sm"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-semibold text-foreground">
                                    {{ comment.author_name }}
                                </h4>
                                <span
                                    v-if="comment.is_approved === false"
                                    class="rounded-full border border-yellow-500/20 bg-yellow-500/10 px-2 py-0.5 text-xs font-medium text-yellow-600 dark:text-yellow-400"
                                >
                                    Pending Approval
                                </span>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                {{
                                    new Date(
                                        comment.created_at,
                                    ).toLocaleDateString()
                                }}
                            </p>
                        </div>
                    </div>
                    <Button
                        variant="ghost"
                        size="sm"
                        @click="startReply(comment.id)"
                        class="text-xs text-muted-foreground hover:text-foreground"
                    >
                        Reply
                    </Button>
                </div>
                <p class="mb-4 text-muted-foreground md:text-foreground">
                    {{ comment.content }}
                </p>

                <!-- Reaction Buttons -->
                <div class="mb-4 flex items-center gap-4">
                    <button
                        @click="toggleReaction(comment.id, 'like')"
                        :disabled="reactionLoading[comment.id]"
                        class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                        :class="
                            commentReactions[comment.id]?.userLiked
                                ? 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
                                : 'bg-muted/50 text-muted-foreground hover:bg-muted'
                        "
                    >
                        <Loader2
                            v-if="reactionLoading[comment.id]"
                            class="h-3 w-3 animate-spin"
                        />
                        <ThumbsUp
                            v-else
                            class="h-3 w-3"
                            :class="
                                commentReactions[comment.id]?.userLiked
                                    ? 'fill-current'
                                    : ''
                            "
                        />
                        <span>{{
                            commentReactions[comment.id]?.likes || 0
                        }}</span>
                    </button>
                    <button
                        @click="toggleReaction(comment.id, 'helpful')"
                        :disabled="reactionLoading[comment.id]"
                        class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                        :class="
                            commentReactions[comment.id]?.userHelpful
                                ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                : 'bg-muted/50 text-muted-foreground hover:bg-muted'
                        "
                    >
                        <Loader2
                            v-if="reactionLoading[comment.id]"
                            class="h-3 w-3 animate-spin"
                        />
                        <HelpCircle
                            v-else
                            class="h-3 w-3"
                            :class="
                                commentReactions[comment.id]?.userHelpful
                                    ? 'fill-current'
                                    : ''
                            "
                        />
                        <span>{{
                            commentReactions[comment.id]?.helpfuls || 0
                        }}</span>
                    </button>
                </div>

                <!-- Reply Form (appears below the comment) -->
                <div
                    v-if="replyFormVisible[comment.id]"
                    :data-reply-form="comment.id"
                    class="mt-4 rounded-lg border border-border bg-muted/30 p-4"
                >
                    <form @submit.prevent="submit" class="space-y-3">
                        <div>
                            <input
                                v-model="form.author_name"
                                type="text"
                                required
                                autocomplete="name"
                                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground placeholder-muted-foreground focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                                placeholder="Your name"
                                aria-label="Your name for reply"
                            />
                            <InputError :message="form.errors.author_name" />
                        </div>
                        <div>
                            <textarea
                                :data-reply-textarea="comment.id"
                                v-model="form.content"
                                rows="3"
                                required
                                :maxlength="MAX_CHARACTERS"
                                class="w-full resize-none rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground placeholder-muted-foreground focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                                :class="
                                    isOverLimit
                                        ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                                        : ''
                                "
                                placeholder="Write a reply..."
                                aria-label="Reply message"
                            ></textarea>
                            <div class="mt-1 flex items-center justify-between">
                                <InputError :message="form.errors.content" />
                                <span
                                    class="text-xs"
                                    :class="
                                        isOverLimit
                                            ? 'font-semibold text-red-500'
                                            : remainingCharacters < 100
                                              ? 'text-yellow-500'
                                              : 'text-muted-foreground'
                                    "
                                >
                                    {{ characterCount }} / {{ MAX_CHARACTERS }}
                                </span>
                            </div>
                            <!-- Honeypot -->
                            <input
                                type="text"
                                name="website"
                                tabindex="-1"
                                autocomplete="off"
                                class="hidden"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <Button
                                type="submit"
                                :disabled="form.processing || isOverLimit"
                                class="bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                            >
                                <Loader2
                                    v-if="form.processing"
                                    class="mr-1.5 h-3 w-3 animate-spin"
                                />
                                <Send v-else class="mr-1.5 h-3 w-3" />
                                {{
                                    form.processing
                                        ? 'Posting...'
                                        : 'Post Reply'
                                }}
                            </Button>
                            <Button
                                type="button"
                                variant="ghost"
                                @click="cancelReply"
                                class="px-3 py-1.5 text-xs text-muted-foreground hover:text-foreground"
                            >
                                Cancel
                            </Button>
                        </div>
                    </form>
                </div>

                <!-- Replies -->
                <div
                    v-if="comment.replies && comment.replies.length > 0"
                    class="mt-6 ml-4 space-y-4 border-l-2 border-border pl-6 md:ml-8"
                >
                    <article
                        v-for="reply in comment.replies"
                        :key="reply.id"
                        class="rounded-lg border border-border/50 bg-muted/30 p-4"
                    >
                        <div class="mb-2 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h5 class="font-semibold text-foreground">
                                    {{ reply.author_name }}
                                </h5>
                                <span
                                    v-if="reply.is_approved === false"
                                    class="rounded-full border border-yellow-500/20 bg-yellow-500/10 px-2 py-0.5 text-xs font-medium text-yellow-600 dark:text-yellow-400"
                                >
                                    Pending
                                </span>
                            </div>
                            <p class="text-xs text-muted-foreground">
                                {{
                                    new Date(
                                        reply.created_at,
                                    ).toLocaleDateString()
                                }}
                            </p>
                        </div>
                        <p
                            class="mb-3 text-sm text-muted-foreground md:text-foreground"
                        >
                            {{ reply.content }}
                        </p>
                        <!-- Reply Reactions -->
                        <div class="flex items-center gap-3">
                            <button
                                @click="toggleReaction(reply.id, 'like')"
                                :disabled="reactionLoading[reply.id]"
                                class="flex items-center gap-1 rounded-lg px-2 py-1 text-xs font-medium transition-colors"
                                :class="
                                    commentReactions[reply.id]?.userLiked
                                        ? 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
                                        : 'bg-muted/50 text-muted-foreground hover:bg-muted'
                                "
                            >
                                <Loader2
                                    v-if="reactionLoading[reply.id]"
                                    class="h-3 w-3 animate-spin"
                                />
                                <ThumbsUp
                                    v-else
                                    class="h-3 w-3"
                                    :class="
                                        commentReactions[reply.id]?.userLiked
                                            ? 'fill-current'
                                            : ''
                                    "
                                />
                                <span>{{
                                    commentReactions[reply.id]?.likes || 0
                                }}</span>
                            </button>
                            <button
                                @click="toggleReaction(reply.id, 'helpful')"
                                :disabled="reactionLoading[reply.id]"
                                class="flex items-center gap-1 rounded-lg px-2 py-1 text-xs font-medium transition-colors"
                                :class="
                                    commentReactions[reply.id]?.userHelpful
                                        ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                        : 'bg-muted/50 text-muted-foreground hover:bg-muted'
                                "
                            >
                                <Loader2
                                    v-if="reactionLoading[reply.id]"
                                    class="h-3 w-3 animate-spin"
                                />
                                <HelpCircle
                                    v-else
                                    class="h-3 w-3"
                                    :class="
                                        commentReactions[reply.id]?.userHelpful
                                            ? 'fill-current'
                                            : ''
                                    "
                                />
                                <span>{{
                                    commentReactions[reply.id]?.helpfuls || 0
                                }}</span>
                            </button>
                        </div>
                    </article>
                </div>
            </article>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="rounded-2xl border border-border bg-card p-12 text-center"
        >
            <MessageCircle
                class="mx-auto mb-4 h-16 w-16 text-muted-foreground/30"
            />
            <h3 class="mb-2 text-lg font-semibold text-foreground">
                No comments yet
            </h3>
            <p class="mb-6 text-muted-foreground">
                Be the first to share your thoughts on this article!
            </p>
            <Button
                v-if="!showForm"
                @click="showForm = true"
                variant="outline"
                class="border-border hover:bg-muted"
            >
                <MessageCircle class="mr-2 h-4 w-4" />
                Add a Comment
            </Button>
        </div>
    </section>
</template>
