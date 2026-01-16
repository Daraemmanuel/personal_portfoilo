import { ref } from 'vue';

export interface ConfirmDialogOptions {
    title?: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'danger' | 'warning' | 'info';
    onConfirm: () => void | Promise<void>;
    onCancel?: () => void;
}

const dialogState = ref<ConfirmDialogOptions | null>(null);

export function useConfirmDialog() {
    const show = (options: ConfirmDialogOptions) => {
        dialogState.value = options;
    };

    const confirm = async () => {
        if (dialogState.value) {
            await dialogState.value.onConfirm();
            dialogState.value = null;
        }
    };

    const cancel = () => {
        if (dialogState.value?.onCancel) {
            dialogState.value.onCancel();
        }
        dialogState.value = null;
    };

    return {
        dialogState,
        show,
        confirm,
        cancel,
    };
}

