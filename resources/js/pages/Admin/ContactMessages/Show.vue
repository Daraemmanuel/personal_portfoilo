<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { ContactMessage } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Mail, User } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    message: ContactMessage;
}>();

// Create properly encoded mailto link
const mailtoLink = computed(() => {
    const email = props.message.email || '';
    if (!email) return '#';
    
    const subject = `Re: ${props.message.subject || 'Contact Message'}`;
    const body = `\n\n--- Original Message ---\nFrom: ${props.message.name}\nDate: ${new Date(props.message.created_at).toLocaleString()}\nSubject: ${props.message.subject}\n\n${props.message.message}`;
    
    return `mailto:${encodeURIComponent(email)}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
});

const handleReplyClick = (e: Event) => {
    if (!props.message.email) {
        e.preventDefault();
        alert('No email address available for this message.');
        return;
    }
    
    // The mailto link will open the default email client
    // If it doesn't work, the user may need to configure their email client
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            {
                title: 'Contact Messages',
                href: route('admin.contact-messages.index'),
            },
            { title: 'View Message', href: '#' },
        ]"
    >
        <Head title="View Message" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2
                            class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                        >
                            Contact Message
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            View message details and respond if needed.
                        </p>
                    </div>
                    <Link :href="route('admin.contact-messages.index')">
                        <Button
                            variant="ghost"
                            class="text-muted-foreground hover:bg-accent hover:text-foreground"
                        >
                            Back to Messages
                        </Button>
                    </Link>
                </div>

                <!-- Message Card -->
                <div
                    class="rounded-2xl border border-border bg-card p-8 shadow-sm backdrop-blur-sm"
                >
                    <div class="space-y-6">
                        <!-- Message Header -->
                        <div class="border-b border-border pb-6">
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"
                                >
                                    <User class="h-6 w-6" />
                                </div>
                                <div>
                                    <h3
                                        class="text-xl font-bold text-foreground"
                                    >
                                        {{ message.name }}
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        {{ message.email }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="mt-4 flex items-center gap-4 text-sm text-muted-foreground/60"
                            >
                                <div class="flex items-center gap-2">
                                    <Mail class="h-4 w-4" />
                                    <span>{{ message.subject }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Calendar class="h-4 w-4" />
                                    <span>{{
                                        new Date(
                                            message.created_at,
                                        ).toLocaleString()
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div>
                            <h4
                                class="mb-3 text-sm font-semibold tracking-wider text-muted-foreground/60 uppercase"
                            >
                                Message
                            </h4>
                            <div
                                class="rounded-lg border border-border bg-muted/30 p-6 whitespace-pre-wrap text-foreground"
                            >
                                {{ message.message }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-end gap-4 border-t border-border pt-6"
                        >
                            <a
                                :href="mailtoLink"
                                @click="handleReplyClick"
                                :class="[
                                    'inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-bold text-primary-foreground shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95',
                                    !message.email ? 'pointer-events-none opacity-50' : 'cursor-pointer'
                                ]"
                            >
                                <Mail class="h-4 w-4" />
                                Reply via Email
                            </a>
                            <Link
                                :href="
                                    route(
                                        'admin.contact-messages.destroy',
                                        message.id,
                                    )
                                "
                                method="delete"
                                as="button"
                                class="rounded-xl border border-destructive/20 bg-destructive/10 px-6 py-2.5 text-sm font-bold text-destructive transition-all hover:bg-destructive/20 active:scale-95"
                            >
                                Delete Message
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
