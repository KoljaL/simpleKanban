// console.info('+page.js');
export const prerender = true;
export const ssr = false;

import { PUBLIC_API_URL } from '$env/static/public';
import { building } from '$app/environment';
import { API_start } from '$lib/api';
import { page } from '$app/stores';
import { onMount } from 'svelte';

/**
 * @description load data to initialize the app
 *
 * @param {Object} fetch
 * @param {Object} params
 * @param {Object} url
 *
 * @returns {Object} data
 */
export const load = async ({ fetch, params, url }) => {
	// load no data if building
	if (building) return;

	// initLocalstorage();

	let dbKey = false;
	let dbName = false;
	// try to get dbKeys from localStorage
	let dbKeys = localStorage.getItem('Skanban-dbKeys') || false;

	// try to get dbKey from url
	if (url.searchParams.get('dbKey')) {
		dbKey = url.searchParams.get('dbKey');
		console.log('dbKey from url', dbKey);
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

	if (!dbKey) {
		if (dbKeys) {
			dbKeys = JSON.parse(dbKeys);
			// console.log('unordered', dbKeys);
			dbKeys.sort((a, b) => (a.timestamp < b.timestamp ? 1 : -1));
			// console.log('ordered', dbKeys);
			dbKey = dbKeys[0].key;
			dbName = dbKeys[0].name;
		} else {
			dbKey = false;
			dbName = false;
		}
	}

	// collect all data to initialize the app
	const customLayout = getCustomLayout();
	customLayout.theme = setTheme();
	const authorName = window.localStorage.getItem('Skanban-Name') || '';
	const db = await API_start(dbKey);
	const data = {
		db: db,
		dbKeys: {
			allKeys: dbKeys,
			currentKey: dbKey,
			currentName: dbName
		},
		user: { name: authorName },
		customLayout: customLayout
	};

	return data;
};

// function API_start(dbKey) {
// 	if (!dbKey) return;
// 	return fetch(PUBLIC_API_URL + 'start&dbKey=' + dbKey)
// 		.then((res) => res.json())
// 		.then((data) => {
// 			return data;
// 		});
// }

function setTheme() {
	var dataTheme;
	dataTheme = window.localStorage.getItem('Skanban-theme') || '';
	if (!dataTheme) {
		dataTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	}
	document.documentElement.setAttribute('data-theme', dataTheme);
	return dataTheme;
}

function getCustomLayout() {
	const customLayout = window.localStorage.getItem('Skanban-customLayout');
	if (customLayout) {
		return JSON.parse(customLayout);
	} else {
		return false;
	}
}

function initLocalstorage() {
	const customLayout = {
		maxWidthPage: 80,
		minWidthColumn: 20
	};
	localStorage.setItem('Skanban-customLayout', JSON.stringify(customLayout));

	const dbKeys = [
		{ key: 'erfgergerfgerg', timestamp: 1682534859698, name: 'dummydata' },
		{ key: 'ergergergerger', timestamp: 1682534859700, name: 'dummydata' },
		{ key: 'htzhtzhjthjtzh', timestamp: 1682534859697, name: 'dummydata' },
		{ key: 'uzkizkuzkzukuz', timestamp: 1682534859696, name: 'dummydata' },
		{ key: 'kuzuzkuzkuzkju', timestamp: 1682534859699, name: 'dummydata' }
	];
	localStorage.setItem('Skanban-dbKeys', JSON.stringify(dbKeys));

	const authorName = 'Skanban User';
	localStorage.setItem('Skanban-Name', authorName);
}
