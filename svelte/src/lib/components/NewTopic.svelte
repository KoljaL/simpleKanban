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

<style>
	.openNewTopicButton:hover {
		filter: brightness(0.7);
		color: var(--green);
	}
</style>
