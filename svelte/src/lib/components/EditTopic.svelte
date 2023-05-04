<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { topicStore, dbKeys, isModalOpen, modalMessage } from '$lib/store.js';
	import { API_editTopic } from '$lib/api.js';
	// COMPONENTS
	import TopicForm from '$lib/components/TopicForm.svelte';
	// ICONS
	import Edit from '$lib/icons/Edit.svelte';

	export let topicId;
	$: dbKey = $dbKeys.currentKey;

	let topicData;
	let openModal = false;

	$: if ($isModalOpen === false) {
		openModal = false;
		// console.log('isModalOpen', $isModalOpen);
	}

	function openEditTopicForm(e) {
		// console.log('topicId', topicId);
		// console.log('edit topic form', e);
		isModalOpen.set(true);
		openModal = true;

		$topicStore.forEach((column) => {
			column.topics.forEach((topic) => {
				if (topic.id === topicId) {
					topicData = topic;
					topicData.position = e;
				}
			});
		});
		// console.log('topicData', topicData);
	}

	function editTopicApiCall(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		let data = Object.fromEntries(formData);
		data.id = topicId;
		console.log('data', data);
		API_editTopic(dbKey, data)
			.then((res) => {
				console.log(res);
				if (res.message === 'success') {
					// let columnId = parseInt(data.column);

					topicStore.update((topicStore) => {
						return topicStore.map((column) => {
							if (column.id === data.column) {
								return {
									...column,
									topics: column.topics.map((topic) => {
										if (topic.id === topicId) {
											return {
												...topic,
												...data
											};
										}
										return topic;
									})
								};
							}
							return column;
						});
					});

					// $topicStore = $topicStore;
					// console.log('topicStore', $topicStore);

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

<button
	class="styleLessButton"
	title="add new Topic"
	on:click={(e) => {
		openEditTopicForm(e);
	}}
>
	<Edit />
</button>
{#if openModal}
	<TopicForm {openModal} {topicData} callback={(e) => editTopicApiCall(e)} />
{/if}
