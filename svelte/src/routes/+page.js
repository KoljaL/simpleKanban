export const prerender = true;
export const ssr = false;
import { building } from '$app/environment';
import { API_start } from '$lib/api';

export const load = async ({ fetch }) => {
	if (building) return;

	const data = { db: await API_start(), dbKey: localStorage.getItem('Skanban-dbKey') || 'abc' };
	return data;
};

// make dbkey to object with a timestamp and a key
// make a select box to select the dbkey
