<script>
	// https://github.com/AndrewLester/svelte-animated-details
	import animatedDetails from 'svelte-animated-details';
	import { formatDatetime } from '$lib/utils.js';

	export let topic;

	function editTopic(e) {
		e.stopPropagation();
		// e.target.parentElement.parentElement.parentElement.preventDefault();
		console.log('edit topic', e);
		console.log('edit topic', e.target.parentElement.parentElement.parentElement);
	}

	function deleteTopic() {
		console.log('delete topic');
	}
</script>

<details id="topic_{topic.id}" class="topicWrapper" use:animatedDetails>
	<summary class="topicHeader">
		<span class="topicTitleWrapper">
			<span class="topicTitle" style="color:{topic.color}">{topic.title}</span>
		</span>
		<span class="topicDate" title={formatDatetime(topic.created)}>
			{formatDatetime(topic.created).split(' ')[0]}
		</span>
	</summary>
	<div class="manageTopic">
		<span class="editTopic" on:click|preventDefault={editTopic} on:keydown={editTopic}>x</span>
		<span class="deleteTopic" on:click={deleteTopic} on:keydown={deleteTopic}>x</span>
	</div>
	<p class="topicContentMain">{topic.content}</p>
	<p class="topicContentFooter">{topic.author}</p>
</details>

<style>
	.topicWrapper {
		overflow: hidden;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-m);
	}
	.topicHeader {
		position: relative;
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

	/* :global(.topicWrapper[open]) .topicTitleWrapper {
		height: 3rem;
		white-space: normal;
	} */
	.topicTitle {
		color: var(--color-text);
	}

	/* .topicHeader::after {
		content: '+';
		width: 1em;
		float: right;
		text-align: center;
		transition: transform 0.3s;
	} */
	/* :global(.topicWrapper[open]) .topicHeader::after {
		content: '-';
	} */

	.manageTopic {
		/* position: absolute; */
		right: 0.5rem;
		top: 0.5rem;
		z-index: 10;
		pointer-events: none;
		display: none;
	}

	:global(.topicWrapper[open]) .manageTopic {
		display: block;
	}

	.topicWrapper > p {
		padding-inline: 0.5rem;
	}

	.topicDate {
		font-size: 0.8rem;
	}
	.topicContentMain {
		padding-bottom: 0.5rem;
	}
	.topicContentFooter {
		font-size: 0.8rem;
	}
</style>
