<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { getDatetimeNow } from '$lib/utils.js';
	import { topicStore, isModalOpen, dbKeys, modalMessage } from '$lib/store.js';
	import { onMount } from 'svelte';
	import ColumnForm from '$lib/components/ColumnForm.svelte';
	import { API_addColumn } from '$lib/api.js';
	export let columnId = null;

	let dbKey = $dbKeys.currentKey;
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
		window.localStorage.setItem('Skanban-Name', data.author);
		API_addColumn(dbKey, data)
			.then((res) => {
				// console.log(res);
				if (res.message === 'success') {
					// delete data.column;
					data.id = res.column_id;
					// add data to column in store
					var col = $topicStore.find((col) => col.id === columnId);
					col.columns.push(data);
					$topicStore = $topicStore;
					// console.log($topicStore[columnId]);
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

<ColumnForm {openModal} {columnData} callback={(e) => createNewColumn(e)} />

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
