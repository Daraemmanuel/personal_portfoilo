import { ref } from 'vue';

const message = ref('');

export function useAriaLive() {
    const announce = (text: string, priority: 'polite' | 'assertive' = 'polite') => {
        // Clear previous message to force re-announcement
        message.value = '';
        
        // Use setTimeout to ensure the clear happens before setting new message
        setTimeout(() => {
            message.value = text;
            
            // Clear after announcement to allow re-announcement of same message
            setTimeout(() => {
                message.value = '';
            }, 1000);
        }, 100);
    };

    return {
        message,
        announce,
    };
}

