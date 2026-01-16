<script setup lang="ts">
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import ConfirmDialog from './ConfirmDialog.vue';

const { dialogState, confirm, cancel } = useConfirmDialog();
</script>

<template>
    <ConfirmDialog
        v-if="dialogState"
        :title="dialogState.title"
        :message="dialogState.message"
        :confirm-text="dialogState.confirmText"
        :cancel-text="dialogState.cancelText"
        :variant="dialogState.variant"
        @confirm="confirm"
        @cancel="cancel"
    />
</template>

