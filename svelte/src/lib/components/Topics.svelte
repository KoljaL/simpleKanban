<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { onMount } from 'svelte';
	// https://github.com/AndrewLester/svelte-animated-details
	import animatedDetails from 'svelte-animated-details';
	import { formatDatetime } from '$lib/utils.js';
	import { dndzone, SOURCES, TRIGGERS } from 'svelte-dnd-action';
	import { topicStore, isModal } from '$lib/store.js';
	import TopicForm from '$lib/components/TopicForm.svelte';
	import EditTopic from '$lib/components/EditTopic.svelte';

	// ICONS
	import Edit from '$lib/icons/Edit.svelte';
	import Delete from '$lib/icons/Delete.svelte';

	export let columnId;
	export let topics;
	const flipDurationMs = 200;
	let dragTopicDisabled = true;
	let edit = false;
	let topicToEdit;
	// $: console.log('isModal', $isModal);
	$: if ($isModal === false) {
		edit = false;
		// console.log('isModal', $isModal);
	}
	// $: edit = $isModal;

	onMount(() => {
		getTopicPositions();

		// document.addEventListener('keypress', (e) => {
		// 	console.log('click', e);
		// });
	});

	// function editTopicForm(e, topicId) {
	// 	// console.log('edit topic form', e);
	// 	edit = true;
	// 	isModal.set(true);
	// 	$topicStore.forEach((column) => {
	// 		column.topics.forEach((topic) => {
	// 			if (topic.id === topicId) {
	// 				topicToEdit = topic;
	// 				topicToEdit.position = e;
	// 			}
	// 		});
	// 	});
	// 	// console.log('topicToEdit', topicToEdit);
	// }

	// function editTopic(e) {
	// 	console.log('edit topic', e);
	// 	edit = false;
	// }

	function deleteTopic(topicId) {
		console.log('delete topic', topicId);
	}

	/**
	 * @description get the positions of the columns and topics
	 * @returns {array} topicPositions
	 */
	function getTopicPositions() {
		let topicPositions = [];
		$topicStore.forEach((column) => {
			// console.log('element', column);
			column.topics.forEach((topic) => {
				// console.log('topic', topic);
				topicPositions.push({
					id: topic.id,
					position: topic.position,
					column_id: column.id,
					name: topic.title
				});
			});
		});
		// console.log('topicPositions', topicPositions);
		return topicPositions;
	}

	//
	//
	// DRAG&DROP TOPICS
	//
	//
	function handleDragTopic(cid, e) {
		const {
			items: newItems,
			info: { source, trigger }
		} = e.detail;
		const colIdx = $topicStore.findIndex((c) => c.id === cid);
		// console.log('Drag cid', cid);
		// console.log('Drag colIdx', colIdx);
		$topicStore[colIdx].topics = e.detail.items;
		$topicStore = [...$topicStore];

		if (source === SOURCES.KEYBOARD && trigger === TRIGGERS.DRAG_STOPPED) {
			dragTopicDisabled = true;
		}
	}

	function handleDropTopic(cid, e) {
		const {
			items: newItems,
			info: { source }
		} = e.detail;
		const colIdx = $topicStore.findIndex((c) => c.id === cid);
		// console.log('Finalize colIdx', colIdx);
		let topics = e.detail.items;
		topics.forEach((t, i) => {
			t.position = i;
		});
		// console.log('topics after', topics);
		$topicStore[colIdx].topics = topics;
		$topicStore = [...$topicStore];

		// update position in db
		fetch(PUBLIC_API_URL + 'updatetopicpositions', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(getTopicPositions())
		})
			.then((res) => res.json())
			.then((data) => {
				console.log('data', data);
			});
		if (source === SOURCES.POINTER) {
			dragTopicDisabled = true;
		}
	}
	function handleClickOnTopic(e) {
		// console.log(e);
		dragTopicDisabled = true;
		// console.log(e.target.firstChild);
		if ((e.type === 'keydown' && e.key === 'Enter') || e.key === ' ') {
			// simulate a mouseklick
			let open = e.target.firstChild.getAttribute('open');
			console.log('open', open);
			if (open === null) {
				e.target.firstChild.setAttribute('open', 'true');
			} else {
				e.target.firstChild.removeAttribute('open');
			}
		}
	}

	function startDragByHandle(e) {
		e.preventDefault();
		dragTopicDisabled = false;
	}
	function handleKeyDownByHandle(e) {
		if ((e.key === 'Enter' || e.key === ' ') && dragTopicDisabled) dragTopicDisabled = false;
	}

	//
	// reactive console
	//
	// $: console.log('dragTopicDisabled', dragTopicDisabled);
</script>

<!-- {#if edit} -->
<TopicForm openModal={edit} topicData={topicToEdit} callback={(e) => editTopic(e)} />
<!-- {/if} -->

<ul
	class="column_content"
	use:dndzone={{
		items: topics,
		dragDisabled: dragTopicDisabled,
		flipDurationMs,
		dropTargetStyle: {
			outline: 'rgba(255, 64, 0, 0.5) solid 2px',
			outlineOffset: '2px',
			'border-radius': '5px'
		}
	}}
	on:consider={(e) => handleDragTopic(columnId, e)}
	on:finalize={(e) => handleDropTopic(columnId, e)}
>
	{#each topics as topic (topic.id)}
		<li on:click={handleClickOnTopic} on:keydown={handleClickOnTopic}>
			<details id="topic_{topic.id}" class="topicWrapper" use:animatedDetails>
				<summary
					class="topicHeader dragHandle"
					aria-label="drag-handle"
					style={dragTopicDisabled ? 'cursor: grab' : 'cursor: grabbing'}
					on:mousedown={startDragByHandle}
					on:touchstart={startDragByHandle}
					on:keydown={handleKeyDownByHandle}
					tabindex="-1"
				>
					<span class="topicTitleWrapper">
						<span class="topicTitle" style="color:{topic.color}">{topic.title}</span>
					</span>
					<span class="topicDate" title={formatDatetime(topic.created)}>
						{formatDatetime(topic.created).split(' ')[0]}
					</span>
				</summary>
				<div class="manageTopic">
					<EditTopic topicId={topic.id} />
					<!-- <EditTopic openModal={edit} topicData={topicToEdit} callback={(e) => editTopic(e)} /> -->
					<!-- <button
						class="editTopicButton styleLessButton small"
						title="edit Topic"
						on:click={(e) => {
							editTopicForm(e, topic.id);
						}}
						on:keydown={(e) => {
							editTopicForm(e, topic.id);
						}}
					>
						<Edit />
					</button> -->
					<button
						class="deleteTopicButton styleLessButton small"
						title="remove Topic"
						on:click={(e) => {
							deleteTopic(e, topic.id);
						}}
						on:keydown={(e) => {
							deleteTopic(e, topic.id);
						}}
					>
						<Delete />
					</button>
				</div>
				<p class="topicContentAuthor">{topic.author}</p>
				<p class="topicContentMain">{topic.content}</p>
				{#if topic.deadline}
					<p class="topicContentDeadline">Deadline: <span>{topic.deadline}</span></p>
				{/if}
			</details>
		</li>
	{/each}
</ul>

<style>
	.column_content {
		list-style: none;
		padding-inline: 0.25rem;
		min-height: 2rem;
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}

	.topicWrapper {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-m);
		box-shadow: var(--shadow-4);
	}

	:global(.topicWrapper[open].extended) {
		transform: scale(1.5);
	}

	.topicHeader {
		display: flex;
		justify-content: space-between;
		align-items: center;
		cursor: pointer;
		padding: 0.25rem;
		padding-inline: 0.5rem;
		padding-top: 0.4rem;
		border-bottom: 1px solid var(--color-border);
		margin-bottom: -1px;
		background-color: var(--bg-color-secondary);
	}
	.topicHeader::-webkit-details-marker {
		display: none;
	}

	.topicTitleWrapper {
		font-weight: bold;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		padding-right: 1rem;
		height: 1.5rem;
		transition: all 0.2s;
		color: var(--color-text-secondary);
	}

	.topicTitle {
		color: var(--color-text);
	}

	.manageTopic {
		position: absolute;
		width: 100%;
		display: flex;
		justify-content: end;
	}

	.manageTopic > button {
		display: inline-block;
		padding: 0.25rem;
		border-radius: 50%;
		margin-left: 0.25rem;
		cursor: pointer;
		transition: all 0.2s;
	}

	.topicWrapper > p {
		padding-inline: 0.5rem;
	}

	.topicDate {
		font-size: 0.8rem;
	}

	.topicContentMain {
		margin-block: 0.5rem;
		padding-bottom: 0.5rem;
	}
	.topicContentAuthor {
		margin-top: 0.5rem;
		margin-bottom: 0rem;
		font-size: 0.8rem;
	}
	.topicContentDeadline {
		margin-top: 0.5rem;
		margin-bottom: 0rem;
		font-size: 0.8rem;
	}
	.topicContentDeadline span {
		color: var(--color-svelte);
	}
</style>
