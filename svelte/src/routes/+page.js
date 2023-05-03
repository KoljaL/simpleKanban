// console.info('+page.js');
export const prerender = true;
export const ssr = false;

// import { PUBLIC_API_URL } from '$env/static/public';
import { building } from '$app/environment';
import { API_start } from '$lib/api';
// import { page } from '$app/stores';
// import { onMount } from 'svelte';
// let dbKey = 'ergergergerger';

// let dbKey = false;
// let dbName = false;
let dbKeys = {};

// http://localhost:5173/?dbKey=ergergergerger
// http://localhost:5173/?dbKey=test

/**
 * @description load data to initialize the app
 *
 * @param {Object} fetch
 * @param {Object} params
 * @param {Object} url
 *
 * @returns {Object} data
 */
export const load = async ({ url }) => {
	// load no data if building
	if (building) return;

	// initLocalstorage();

	// get dbKey from url
	let dbKey = url.searchParams.get('dbKey') || false;
	// try to get dbKeys from localStorage
	let dbKeysLocal = localStorage.getItem('Skanban-dbKeys') || [];

	// if no dbKey in url and no dbKeys in localStorage, return because no data to load
	if (!dbKey && !dbKeysLocal.length > 0) {
		console.log('no dbKey and no dbKeysLocal');
		return;
	}
	/// debug
	// else {
	// 	console.log('dbKey', dbKey);
	// 	console.log('dbKeysLocal', dbKeysLocal);
	// }

	// handle dbKeys from localStorage
	if (dbKeysLocal.length > 0) {
		dbKeys = JSON.parse(dbKeysLocal);
		// if dbKey in url, add it to dbKeys
		if (dbKey) {
			// find dbKey in dbKeys and replace it with new timestamp
			dbKeys = dbKeys.filter((item) => item.key !== dbKey);
			dbKeys.push({ key: dbKey, timestamp: Date.now() });
		}
		// sort dbKeys by timestamp
		dbKeys.sort((a, b) => (a.timestamp < b.timestamp ? 1 : -1));
		dbKey = dbKeys[0].key;
	}
	// if no dbKeys in localStorage, add dbKey from url
	else {
		dbKeys = [{ key: dbKey, timestamp: Date.now() }];
	}

	// save dbKeys to localStorage
	localStorage.setItem('Skanban-dbKeys', JSON.stringify(dbKeys));

	// // try to get dbKey from url
	// if (url.searchParams.get('dbKey')) {
	// 	dbKey = url.searchParams.get('dbKey');
	// 	console.log('dbKey from url', dbKey);
	// 	const dbKeysOld = localStorage.getItem('Skanban-dbKeys');
	// 	// daa key from url to localStorage keys
	// 	if (dbKeysOld) {
	// 		const dbKeysOldObj = JSON.parse(dbKeysOld);
	// 		dbKeysOldObj.push({ key: dbKey, timestamp: Date.now() });
	// 		localStorage.setItem('Skanban-dbKeys', JSON.stringify(dbKeysOldObj));
	// 	} else {
	// 		localStorage.setItem(
	// 			'Skanban-dbKeys',
	// 			JSON.stringify([{ key: dbKey, timestamp: Date.now() }])
	// 		);
	// 	}
	// }
	// if (!dbKey) {
	// 	if (dbKeys) {
	// 		dbKeys = JSON.parse(dbKeys);
	// 		// console.log('unordered', dbKeys);
	// 		dbKeys.sort((a, b) => (a.timestamp < b.timestamp ? 1 : -1));
	// 		// console.log('ordered', dbKeys);
	// 		dbKey = dbKeys[0].key;
	// 		// dbName = dbKeys[0].name;
	// 	} else {
	// 		dbKey = false;
	// 		// dbName = false;
	// 	}
	// }

	//
	// collect all data to initialize the app
	//
	const customLayout = await getCustomLayout();
	// console.log('customLayout', customLayout);
	setTheme();
	const authorName = window.localStorage.getItem('Skanban-Name') || '';
	const db = await API_start(dbKey);
	const data = {
		db: db,
		dbKeys: {
			allKeys: dbKeys,
			currentKey: dbKeys[0].key
		},
		user: { name: authorName },
		customLayout: customLayout
	};

	return data;
};

function setTheme() {
	var dataTheme;
	dataTheme = window.localStorage.getItem('Skanban-theme') || '';
	if (!dataTheme) {
		dataTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	}
	document.documentElement.setAttribute('data-theme', dataTheme);
	return dataTheme;
}

async function getCustomLayout() {
	let customLayout = window.localStorage.getItem('Skanban-customLayout');
	if (customLayout) {
		try {
			customLayout = JSON.parse(customLayout);
			return customLayout;
		} catch (e) {
			console.log('error parsing customLayout', e);
			return { pageWidth: '50', columnWidth: '15' };
		}
	} else {
		return { pageWidth: '50', columnWidth: '15' };
	}
}

function initLocalstorage() {
	const customLayout = {
		pageWidth: 80,
		columnWidth: 20
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
