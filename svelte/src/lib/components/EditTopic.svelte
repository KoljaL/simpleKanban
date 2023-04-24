<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { topicStore, isModal, modalMessage } from '$lib/store.js';

	// COMPONENTS
	import TopicForm from '$lib/components/TopicForm.svelte';
	// ICONS
	import Edit from '$lib/icons/Edit.svelte';

	export let topicId;
	let topicData;
	let openModal = false;

	$: if ($isModal === false) {
		openModal = false;
		// console.log('isModal', $isModal);
	}

	function openEditTopicForm(e) {
		// console.log('topicId', topicId);
		// console.log('edit topic form', e);
		isModal.set(true);
		openModal = true;

		$topicStore.forEach((column) => {
			column.topics.forEach((topic) => {
				if (topic.id === topicId) {
					topicData = topic;
					topicData.position = e;
				}
			});
		});
		console.log('topicData', topicData);
	}

	function editTopicApiCall(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		let data = Object.fromEntries(formData);
		data.id = topicId;
		console.log('data', data);

		fetch(PUBLIC_API_URL + 'editTopic', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		})
			.then((res) => res.json())
			.then((res) => {
				// console.log(res);
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

					// find index of column in $topicStore
					// let columnIndex;
					// $topicStore.forEach((column) => {
					// 	if (column.id === data.column) {
					// 		columnIndex = $topicStore.indexOf(column);
					// 	}
					// });

					// let topicIndex;
					// $topicStore[columnIndex].topics.forEach((topic) => {
					// 	if (topic.id === topicId) {
					// 		topicIndex = $topicStore[columnIndex].topics.indexOf(topic);
					// 	}
					// });
					// $topicStore[columnIndex].topics[topicIndex] = { ...data };
					// $topicStore = $topicStore;

					// console.log('index', $topicStore[columnIndex].topics[topicIndex]);

					// console.log('topicStore', $topicStore);
					// console.log('columnId', $topicStore[columnId]);

					// var col = $topicStore.find((col) => col.id == columnId);
					// var top = col.topics.find((top) => top.id === topicId);
					// console.log('top', top);
					// make data to array
					// data = [data];
					// console.log('data', data);
					// col.push(data);

					// add new topic to store
					// $topicStore[columnId].topics.push(data);

					// // update $topicStore with data
					// let index;
					// $topicStore[columnId].topics.forEach((topic) => {
					// 	if (topic.id === topicId) {
					// 		index = $topicStore[columnId].topics.indexOf(topic);
					// 	}
					// });

					// $topicStore = $topicStore[columnId].topics[index] = { ...data };
					// console.log('topicStore', $topicStore[columnId].topics[index]);
					console.log('topicStore', $topicStore);

					// let oldTopic = $topicStore[columnId].find((topic) => topic.id === topicId);
					// console.log('oldTopic', oldTopic);
					// oldTopic = data;

					//  $topicStore
					// 	.find((column) => column.id === data.column)
					// 	.topics.find((topic) => topic.id === topicId)

					// $topicStore.forEach((column) => {
					// 	column.topics.forEach((topic) => {
					// 		if (topic.id === topicId) {
					// 			console.log('topic', topic);
					// 			topic = data;
					// 			console.log('topic', topic);
					// 		}
					// 	});
					// });

					// delete data.column;
					// data.id = res.topic_id;
					// // add data to column in store
					// var col = $topicStore.find((col) => col.id === data.column);
					// col.topics.push(data);
					$topicStore = $topicStore;
					console.log('topicStore', $topicStore);

					// console.log($topicStore[columnId]);
					isModal.set(false);
					openModal = false;
				} else {
					$modalMessage = res.message;
				}
			})
			.catch((err) => console.log(err));
	}

	// function createNewTopic(e) {
	// 	e.preventDefault();
	// 	// console.log(e.target);
	// 	const formData = new FormData(e.target);
	// 	let data = Object.fromEntries(formData);
	// 	data.created = getDatetimeNow();
	// 	window.localStorage.setItem('SkanbanName', data.author);

	// }

	// $: console.log('$topicStore', $topicStore);
</script>

<button
	class="openNewTopicButton styleLessButton"
	title="add new Topic"
	on:click={(e) => {
		openEditTopicForm(e);
	}}
>
	<Edit />
</button>

<TopicForm {openModal} {topicData} callback={(e) => editTopicApiCall(e)} />
