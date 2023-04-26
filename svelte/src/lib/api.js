import { PUBLIC_API_URL } from '$env/static/public';
import { dbHash } from '$lib/store.js';
import { onMount } from 'svelte';
let $dbHash = 'abs';
// onMount(() => {
// 	$dbHash = localStorage.getItem('Skanban-dbHash') || 'abc';
// });

// console.log(PUBLIC_API_URL);
// const dbHash = $dbHash;
/**
 * @description API call to get all data to initialize the app
 */
export function API_start() {
	return fetch(PUBLIC_API_URL + 'start&db=' + $dbHash)
		.then((res) => res.json())
		.then((data) => {
			return data;
		});
}

export function API_editTopic(data) {
	return fetch(PUBLIC_API_URL + 'editTopic&db=' + $dbHash, {
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

export function API_addTopic(data) {
	return fetch(PUBLIC_API_URL + 'addTopic&db=' + $dbHash, {
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
	return fetch(PUBLIC_API_URL + 'updatecolumnpositions&db=' + $dbHash, {
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
	return fetch(PUBLIC_API_URL + 'updatetopicpositions&db=' + $dbHash, {
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
