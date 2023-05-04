<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { topicStore, dbKeys, isModalOpen, modalMessage } from '$lib/store.js';
	import { API_editColumn } from '$lib/api.js';
	// COMPONENTS
	import ColumnForm from '$lib/components/ColumnForm.svelte';
	$: dbKey = $dbKeys.currentKey;

	let columnId;
	let openModal = false;

	let columnData = {
		id: '',
		position: '',
		column: '',
		title: '',
		color: '#abb2bf'
	};

	$: if ($isModalOpen === false) {
		openModal = false;
		// console.log('isModalOpen', $isModalOpen);
	}

	function openEditColumnForm(e) {
		columnData = $topicStore.find((column) => column.id === columnId);
		console.log('columnData', columnData);
		isModalOpen.set(true);
		openModal = true;
	}

	function editColumnApiCall(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		let data = Object.fromEntries(formData);
		data.id = columnId;
		console.log('data', data);
		API_editColumn(dbKey, data)
			.then((res) => {
				if (res.message === 'success') {
					console.log(data);

					columnData.column_name = data.column_name;
					columnData.color = data.color;

					topicStore.update((old) => {
						const id = old.findIndex((column) => column.id === data.id);
						old[id] = columnData;
						return old;
					});
					$topicStore = $topicStore;

					isModalOpen.set(false);
					openModal = false;
				} else {
					$modalMessage = res.message;
				}
			})
			.catch((err) => console.log(err));
	}

	// $: console.log('$topicStore', $topicStore);
</script>

{#if $topicStore}
	<select
		class=""
		title="edit Column"
		bind:value={columnId}
		on:change={(e) => {
			openEditColumnForm(e);
		}}
	>
		{#each $topicStore as item}
			<option value={item.id}>
				{item.column_name}
			</option>
		{/each}
	</select>
{/if}

{#if openModal}
	<ColumnForm {openModal} {columnData} callback={(e) => editColumnApiCall(e)} />
{/if}
