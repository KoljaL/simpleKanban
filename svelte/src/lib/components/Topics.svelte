<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { onMount } from 'svelte';
	// https://github.com/AndrewLester/svelte-animated-details
	import animatedDetails from 'svelte-animated-details';
	import { formatDatetime, md } from '$lib/utils.js';
	import { dndzone, SOURCES, TRIGGERS } from 'svelte-dnd-action';
	import { topicStore, isModalOpen, dbKeys } from '$lib/store.js';

	import TopicForm from '$lib/components/TopicForm.svelte';
	import EditTopic from '$lib/components/EditTopic.svelte';
	import ExpandedTopic from '$lib/components/ExpandedTopic.svelte';
	import { API_updateTopicPositions } from '$lib/api.js';
	// ICONS
	import Shrink from '$lib/icons/Shrink.svelte';
	import Expand from '$lib/icons/Expand.svelte';
	import Delete from '$lib/icons/Delete.svelte';

	export let columnId;
	export let topics;

	// $dbKeys = data?.dbKeys || [];
	let dbKey = $dbKeys.currentKey;

	const flipDurationMs = 200;
	let dragTopicDisabled = true;
	let editTopic = false;
	let topicToEdit;
	let expanded = false;
	$: if ($isModalOpen === false) {
		editTopic = false;
		// console.log('isModalOpen', $isModalOpen);
	}

	onMount(() => {
		getTopicPositions();
	});

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

		// update position in db via API
		const data = getTopicPositions();
		API_updateTopicPositions(dbKey, data).then((data) => {
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
		setTimeout(() => {
			// dragTopicDisabled = true;
			// e.preventDefault();
			e.target.style.color = 'red';
			// console.log('startDragByHandle');
		}, 100);
		e.preventDefault();

		dragTopicDisabled = false;
	}
	function handleKeyDownByHandle(e) {
		if ((e.key === 'Enter' || e.key === ' ') && dragTopicDisabled) dragTopicDisabled = false;
	}

	function handleDblClick(e) {
		e.preventDefault();
		let element = e.target;
		if (element.tagName !== 'DETAILS') {
			element = element.closest('details');
		}
		element.querySelector('.expandTopicButton').click();
		console.log('dblclick');
	}
	//
	// reactive console
	//
	// $: console.log('dragTopicDisabled', dragTopicDisabled);
</script>

<!-- callback={(e) => editTopic(e)}  -->
<TopicForm openModal={editTopic} topicData={topicToEdit} />

<ul
	class="column_content"
	use:dndzone={{
		items: topics,
		dragDisabled: dragTopicDisabled,
		flipDurationMs,
		dropTargetStyle: {
			outline: 'rgba(255, 64, 0, 0.5) solid 2px',
			outlineOffset: '1px',
			'border-radius': '5px'
		}
	}}
	on:consider={(e) => handleDragTopic(columnId, e)}
	on:finalize={(e) => handleDropTopic(columnId, e)}
>
	{#each topics as topic (topic.id)}
		<li
			class="listItem"
			on:click={handleClickOnTopic}
			on:keydown={handleClickOnTopic}
			on:dblclick={(e) => handleDblClick(e)}
		>
			<details
				id="topic_{topic.id}"
				class="topicWrapper boxShadow"
				class:expanded
				use:animatedDetails
			>
				<summary
					class="topicHeader dragHandle"
					aria-label="drag-handle"
					style={dragTopicDisabled ? 'cursor: grab' : 'cursor: grabbing'}
					on:mousedown={startDragByHandle}
					on:keydown={handleKeyDownByHandle}
					on:touchstart={startDragByHandle}
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
					<ExpandedTopic topicId={topic.id} />
					<EditTopic topicId={topic.id} />
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
				<p class="topicContentMain">{@html md(topic.content)}</p>

				<div class="topicFooter">
					<span class="topicContentAuthor" style="color:{topic.color}">
						<span>Author: </span>{topic.author}
					</span>

					{#if topic.deadline}
						<span class="topicContentDeadline">Deadline: <span>{topic.deadline}</span></span>
					{/if}
				</div>
			</details>
		</li>
	{/each}
</ul>

<style>
	.column_content {
		max-height: 100%;
		overflow-y: auto;
		list-style: none;
		margin: 0;
		padding-inline: 0.25rem;
		min-height: 2rem;
		display: flex;
		flex-direction: column;
		gap: 0.25rem;
	}

	.listItem {
		position: relative;
	}

	.topicWrapper {
		position: relative;
		overflow: hidden;
		margin-block: 0.25rem;
		/* background-color: var(--bg-color-secondary); */
		/* border: 1px solid var(--border-color); */
		/* border-radius: var(--border-radius-m); */
		/* box-shadow: var(--box-shadow); */
		--transition: all 0.1s linear;
	}
	/* .topicWrapper:after {
		position: absolute;
		top: 0;
		left: 0;
		display: block;
		width: 100%;
		height: 100%;
		pointer-events: none;
		content: '';
		border-radius: inherit;
		box-shadow: var(--box-shadow-after);
	} */

	.topicHeader {
		display: flex;
		justify-content: space-between;
		align-items: center;
		cursor: pointer;
		padding: 0.25rem;
		padding-inline: 0.5rem;
		padding-top: 0.4rem;
		border-bottom: 1px solid var(--border-color);
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
		transition: var(--transition);
		color: var(--color-text-secondary);
	}

	.topicTitle {
		color: var(--color-text);
	}

	.manageTopic {
		position: absolute;
		top: 0.15rem;
		right: 0rem;
		width: max-content;
		display: flex;
		justify-content: end;
		gap: 0.5rem;
		padding: 0.25rem;
		opacity: 0;
		transition: var(--transition);
	}

	:global(.topicWrapper[open]) .manageTopic {
		opacity: 1;
	}

	.topicWrapper > * {
		padding-inline: 0.5rem;
	}

	.topicDate {
		font-size: 0.8rem;
		opacity: 1;
		transition: var(--transition);
	}
	:global(.topicWrapper[open]) .topicDate {
		transition: var(--transition);
		opacity: 0;
	}

	.topicContentMain {
		padding-top: 1rem;
		margin-block: 0.5rem;
		padding-bottom: 0.5rem;
	}

	.topicFooter {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0.25rem;
		padding-inline: 0.5rem;
		border-top: 1px solid var(--border-color);
		background-color: var(--bg-color-secondary);
	}

	.topicContentAuthor {
		margin-top: 0.5rem;
		margin-bottom: 0rem;
		font-size: 0.8rem;
	}
	.topicContentAuthor > span {
		color: var(--color-text);
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
