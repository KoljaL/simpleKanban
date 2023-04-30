<script>
	import { topicStore, isModalOpen } from '$lib/store.js';
	import { formatDatetime, md } from '$lib/utils.js';
	import Modal from '$lib/components/Modal.svelte';
	import Expand from '$lib/icons/Expand.svelte';
	import Shrink from '$lib/icons/Shrink.svelte';
	export let topicId;
	let topicData;
	let openModal = false;

	$: if ($isModalOpen === false) {
		openModal = false;
		// console.log('isModalOpen', $isModalOpen);
	}

	function openExpandedTopic(e) {
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
		console.log('topicData', topicData);
	}
</script>

<button
	class="expandTopicButton styleLessButton small"
	title="expand Topic"
	on:click={openExpandedTopic}
>
	<Expand />
</button>

{#if openModal}
	<Modal expanded={true} bind:openModal position={topicData.position}>
		<div class="expandedTopic">
			<div class="topicHeader">
				<h2 class="topicTitle" style="color:{topicData.color}">
					{topicData.title}
				</h2>
			</div>

			<div class="topicDates">
				<span class="topicDate">
					{formatDatetime(topicData.created)}
				</span>
				{#if topicData.deadline}
					<span class="topicContentDeadline">Deadline: <span>{topicData.deadline}</span></span>
				{/if}
			</div>

			<div class="manageTopic" />
			<p class="topicContentAuthor">{topicData.author}</p>
			<p class="topicContentMain">{@html md(topicData.content)}</p>
		</div>
	</Modal>
{/if}

<style>
	.topicHeader {
		display: flex;
		justify-content: space-between;
		align-items: center;
		cursor: pointer;
		padding: 0.25rem;
		padding-inline: 0.5rem;
		padding-top: 0.4rem;
		border-bottom: 1px solid var(--border-color);
		margin-inline: -0.75rem;
		margin-bottom: -1px;
		background-color: var(--bg-color-secondary);
	}

	.topicTitle {
		color: var(--color-text);
		margin: 0;
	}

	.manageTopic {
		position: absolute;
		width: 100%;
		display: flex;
		justify-content: end;
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
