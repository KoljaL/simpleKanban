<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { getDatetimeNow } from '$lib/utils.js';
	import { topicStore, isModalOpen, dbKeys, modalMessage } from '$lib/store.js';
	import { onMount } from 'svelte';
	import TopicForm from '$lib/components/TopicForm.svelte';
	import Plus from '$lib/icons/Plus.svelte';
	import { API_addTopic } from '$lib/api.js';
	export let columnId;
	export let columns;

	let dbKey = $dbKeys.currentKey;
	console.log('dbKey', dbKey);
	let openModal = false;
	let topicData = {
		id: '',
		position: '',
		column: columnId,
		created: '',
		deadline: '',
		author: '',
		title: '',
		content: '',
		color: '#abb2bf'
	};

	$: if ($isModalOpen === false) {
		openModal = false;
		// console.log('isModalOpen', $isModalOpen);
	}

	// $: modalPosition = modalPosition;
	onMount(() => {
		topicData.authorName = window.localStorage.getItem('Skanban-Name') || '';
	});

	function openNewTopicForm(e) {
		topicData.position = e;
		// console.log(topicData);
		openModal = true;
		topicData.columnName = columns[columnId].column_name;
	}

	function createNewTopic(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		let data = Object.fromEntries(formData);
		data.created = getDatetimeNow();
		window.localStorage.setItem('Skanban-Name', data.author);
		API_addTopic(dbKey, data)
			.then((res) => {
				// console.log(res);
				if (res.message === 'success') {
					// delete data.column;
					data.id = res.topic_id;
					// add data to column in store
					var col = $topicStore.find((col) => col.id === columnId);
					col.topics.push(data);
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

<button
	class="openNewTopicButton styleLessButton"
	title="add new Topic"
	on:click={openNewTopicForm}
>
	<Plus />
</button>

<TopicForm {openModal} {topicData} callback={(e) => createNewTopic(e)} />

<!-- {#if openModal}
	<Modal bind:openModal position={modalPosition}>
		<TopicForm {topicData} callback={(e) => createNewTopic(e)} />
	</Modal>
{/if} -->

<style>
	/* .row {
		display: flex;
		flex-direction: row;
		gap: 0.5rem;
	}

	.newTopic_header {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		padding-left: 1rem;
		padding-right: 2rem;
		padding-top: 0.5rem;
		padding-bottom: 0.5rem;
		border-bottom: 1px solid var(--border-color);
	}

	.newTopic_header h2 {
		color: var(--color-text);
		text-align: center;
		font-size: 1.2rem;
		margin: 0;
	}

	.newTopic_form {
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}

	.newTopicMissingInput {
		padding-left: 1rem;
		color: var(--error);
	}

	.column {
		margin-left: 0.5rem;
	}
	textarea {
		height: 5rem;
	} */
</style>
