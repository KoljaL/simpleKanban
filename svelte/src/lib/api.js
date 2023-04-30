import { PUBLIC_API_URL } from '$env/static/public';
// import { dbKeys } from '$lib/store.js';
// import { topicStore, customLayout, dbKeys, user } from '$lib/store.js';

import { onMount } from 'svelte';
import { browser, building, dev, version } from '$app/environment';
// let dbKey = $dbKeys.currentKey;
// let dbName = $dbKeys.currentName;
// console.log('dbKey', $dbKeys);
let dbKey = 'ergergergerger';
if (browser) {
	// try to get dbKeys from localStorage
	let dbKeys = localStorage.getItem('Skanban-dbKeys') || false;
	dbKeys = JSON.parse(dbKeys);
	console.log('dbKeys', dbKeys);
	if (dbKeys) {
		dbKeys.sort((a, b) => (a.timestamp < b.timestamp ? 1 : -1));
		dbKey = dbKeys[0].key;
	}
	console.log('dbKey', dbKey);
}

// console.log(PUBLIC_API_URL);
// const dbKey = $dbKey;
/**
 * @description API call to get all data to initialize the app
 */
export function API_start(dbKey) {
	if (!dbKey) return;

	return fetch(PUBLIC_API_URL + 'start&dbKey=' + dbKey)
		.then((res) => res.json())
		.then((data) => {
			return data;
		});
}

export function API_editTopic(data) {
	return fetch(PUBLIC_API_URL + 'editTopic&dbKey=' + dbKey, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	})
		.then((res) => res.json())
		.then((data) => {
			return data;
		});
}

export function API_addTopic(dbKey, data) {
	return fetch(PUBLIC_API_URL + 'addTopic&dbKey=' + dbKey, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	})
		.then((res) => res.json())
		.then((data) => {
			return data;
		});
}

export function API_updateColumnPositions(data) {
	return fetch(PUBLIC_API_URL + 'updatecolumnpositions&dbKey=' + dbKey, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	})
		.then((res) => res.json())
		.then((data) => {
			return data;
		});
}

export function API_updateTopicPositions(data) {
	return fetch(PUBLIC_API_URL + 'updatetopicpositions&dbKey=' + dbKey, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	})
		.then((res) => res.json())
		.then((data) => {
			return data;
		});
}
