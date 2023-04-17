<script>
	// https://github.com/AndrewLester/svelte-animated-details
	import animatedDetails from 'svelte-animated-details';
	import { draggable } from '@neodrag/svelte';
	import { flip } from 'svelte/animate';
	import { dndzone } from 'svelte-dnd-action';
	import { topicStore } from '$lib/store.js';
	import { slide } from 'svelte/transition';
	let unrelated = false;
	let duration = 400;
	const flipDurationMs = 300;

	// export let columnId;
	export let topic;
	// $: topics = $topicStore[columnId];
</script>

<!-- {#if topics}
	{#each topics as topic (topic.id)}
		<li animate:flip={{ duration: flipDurationMs }}> -->
<details use:animatedDetails>
	<summary>{topic.title}</summary>
	<p class="topic_header">{topic.created}</p>
	<p class="topic_content">{topic.content}</p>
	<p class="topic_footer">{topic.author}</p>
</details>

<!-- </li>
	{/each}
{/if} -->

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
