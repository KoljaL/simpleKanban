import { writable } from 'svelte/store';

export const topicStore = writable(0);
export const isModalOpen = writable(0);
export const modalMessage = writable(0);
export const layoutCustomisation = writable(0);
