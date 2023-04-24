import { writable } from 'svelte/store';

export const topicStore = writable(0);
export const isModal = writable(0);
export const modalMessage = writable(0);
