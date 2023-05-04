<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { getDatetimeNow } from '$lib/utils.js';
	import { topicStore, isModalOpen, dbKeys, modalMessage } from '$lib/store.js';
	import { onMount } from 'svelte';
	import ColumnForm from '$lib/components/ColumnForm.svelte';
	import { API_addColumn } from '$lib/api.js';
	export let columnId = null;

	$: dbKey = $dbKeys.currentKey;
	// console.log('dbKey', dbKey);
	let openModal = false;
	let columnData = {
		id: '',
		position: '',
		column: columnId,
		title: '',
		color: '#abb2bf'
	};

	$: if ($isModalOpen === false) {
		openModal = false;
		// console.log('isModalOpen', $isModalOpen);
	}

	function openNewColumnForm(e) {
		columnData.position = e;
		// console.log(columnData);
		openModal = !openModal;
	}

	function createNewColumn(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		let data = Object.fromEntries(formData);
		data.created = getDatetimeNow();
		// find highest position in $topicstore araays
		data.position =
			$topicStore.reduce(
				(max, p) => (p.position > max ? p.position : max),
				$topicStore[0].position
			) + 1;
		// data.position = Math.floor(Math.random() * 900) + 100;
		console.log(data);
		API_addColumn(dbKey, data)
			.then((res) => {
				// console.log(res);
				if (res.message === 'success') {
					// delete data.column;

					data.id = parseInt(res.column_id);
					// convertr string to number

					data.topics = [];
					// add data to column in store
					// var col = $topicStore.find((col) => col.id === columnId);
					$topicStore.push(data);
					$topicStore = $topicStore;
					console.log($topicStore);
					openModal = false;
				} else {
					$modalMessage = res.message;
				}
			})
			.catch((err) => console.log(err));
	}

	// $: console.log('$topicStore', $topicStore);
</script>

<button class="addColumn ButtonWithIcon" title="add new Column" on:click={openNewColumnForm}>
	add
</button>
{#if openModal}
	<ColumnForm {openModal} {columnData} callback={(e) => createNewColumn(e)} />
{/if}

<style>
	.addColumn {
		opacity: 0.7;
		transition: all 0.2s ease-in-out;
	}

	.addColumn {
		color: var(--green);
	}

	.addColumn:hover {
		opacity: 1;
	}
</style>
