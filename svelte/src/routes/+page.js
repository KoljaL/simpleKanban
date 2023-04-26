// console.info('+page.js');
export const prerender = true;
export const ssr = false;
import { building } from '$app/environment';
import { API_start } from '$lib/api';
import { page } from '$app/stores';
import { onMount } from 'svelte';
import { layoutCustomisation } from '$lib/store.js';
let $layoutCustomisation = {};
export const load = async ({ fetch, params, url }) => {
	// load no data if building
	if (building) return;

	let dbKey = false;
	// try to get dbKeys from localStorage
	const dbKeys = localStorage.getItem('Skanban-dbKeys') || false;

	// try to get dbKey from url
	if (url.searchParams.get('dbKey')) {
		dbKey = url.searchParams.get('dbKey');
		const dbKeysOld = localStorage.getItem('Skanban-dbKeys');
		// daa key from url to localStorage keys
		if (dbKeysOld) {
			const dbKeysOldObj = JSON.parse(dbKeysOld);
			dbKeysOldObj.push({ key: dbKey, timestamp: Date.now() });
			localStorage.setItem('Skanban-dbKeys', JSON.stringify(dbKeysOldObj));
		} else {
			localStorage.setItem(
				'Skanban-dbKeys',
				JSON.stringify([{ key: dbKey, timestamp: Date.now() }])
			);
		}
	}

	$layoutCustomisation.dbKey = dbKey || '';

	if (!dbKey) {
		if (dbKeys) {
			dbKey = JSON.parse(dbKeys)[0].key;
		} else {
			dbKey = false;
		}
	}

	// console.log('dbKey', dbKey);
	// console.log('dbKeys', dbKeys);

	const data = { db: await API_start(dbKey), dbKey: dbKeys };
	return data;
};

// make dbkey to object with a timestamp and a key
// make a select box to select the dbkey
