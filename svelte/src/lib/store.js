// console.info('store.js');

import { writable } from 'svelte/store';

export const topicStore = writable(0);
export const isModalOpen = writable(0);
export const modalMessage = writable(0);
export const customLayout = writable(0);
export const user = writable(0);
export const dbKeys = writable(0);
