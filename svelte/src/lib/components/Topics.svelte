<script>
	// https://github.com/AndrewLester/svelte-animated-details
	import animatedDetails from 'svelte-animated-details';
	import { topicStore } from '$lib/store.js';
	// $topicStore;
	import { slide } from 'svelte/transition';
	let unrelated = false;
	let duration = 400;

	export let columnId;
	// console.log(columnId);
	// console.log($topicStore);
	// console.log($topicStore[columnId]);
	// export let topics;
	// console.log(topics);
	$: topics = $topicStore[columnId];
</script>

{#if topics}
	{#each topics as topic}
		<li>
			<details use:animatedDetails>
				<summary>{topic.title}</summary>
				<p class="topic_header">{topic.created}</p>
				<p class="topic_content">{topic.content}</p>
				<p class="topic_footer">{topic.author}</p>
			</details>
		</li>
	{/each}
{/if}

<style>
	details {
		overflow: hidden;
		--border-radius-topic: 0.25rem;
		border: 1px solid var(--v-lightDark);
		border-radius: var(--border-radius-topic);
	}
	summary {
		display: block;
		cursor: pointer;
		padding: 0.25rem;
		padding-left: 0.4rem;
		padding-top: 0.4rem;
		border-bottom: 1px solid var(--v-lightDark);
		margin-bottom: -1px;
		background-color: var(--bg-color-secondary);
	}
	summary::-webkit-details-marker {
		display: none;
	}

	summary::after {
		content: '+';
		width: 1em;
		float: right;
		text-align: center;
		transition: transform 0.3s;
	}
	:global(details[open]) summary::after {
		/* transform: rotate(90deg); */
		content: '-';
	}

	details > p {
		padding-inline: 0.5rem;
	}

	.topic_header {
		font-size: 0.8rem;
	}
	.topic_content {
		padding-bottom: 0.5rem;
	}
	.topic_footer {
		font-size: 0.8rem;
	}
</style>
