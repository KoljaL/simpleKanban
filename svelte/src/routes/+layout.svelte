<script>
	// console.info('+layout.svelte');
	import Header from '$lib/layout/Header.svelte';
	import Footer from '$lib/layout/Footer.svelte';
	import ScrollArea from '$lib/components/ScrollArea.svelte';
	import { customLayout, topicStore } from '$lib/store.js';
	import { setDebug } from '$lib/utils.js';
	import { page } from '$app/stores';
	import { Head } from 'svead';
	setDebug(0);

	// $: $customLayout = $customLayout;
	$: $customLayout?.pageWidth;
	$: $customLayout?.columnWidth;

	deb.y('topicStsore', $topicStore);
	$: deb.y('$customLayout.', $customLayout);

	let title = 'Skanban';
	let description = 'A Kanban made with Svelte';
	let url = $page.url.toString();

	import '$lib/styles/styles.css';
</script>

<Head {title} {description} {url} />

<svelte:head>
	{#if $customLayout.pageWidth && $customLayout.columnWidth}
		{deb.r('customLayout style to head', $customLayout)}
		{@html `
  <style>
    :root {
			--max-width-page: ${$customLayout.pageWidth}vw;
      --min-width-column: ${$customLayout.columnWidth}rem;
    }
    </style>
    `}
	{/if}
</svelte:head>

<Header />
<main>
	<!-- <ScrollArea> -->
	<slot />
	<!-- </ScrollArea> -->
</main>

<Footer />

<style>
	:global(#SKA > header) {
		width: 100%;
		max-width: var(--max-width-page);
		height: var(--header-height);
		padding: 0;
		padding-inline: 1rem;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		/* color: var(--color-svelte); */
		transition: width 0.5s ease-in-out, max-width 0.5s ease-in-out;
	}

	:global(#SKA) > main {
		position: relative;
		display: flex;
		width: 100%;
		max-width: var(--max-width-page);
		height: var(--main-height);
		padding-inline: 0.5rem;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		transition: width 0.5s ease-in-out, max-width 0.5s ease-in-out;
	}

	:global(#SKA > footer) {
		width: 100%;
		max-width: var(--max-width-page);
		height: var(--footer-height);
		padding-inline: 1rem;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-text);
		transition: width 0.5s ease-in-out, max-width 0.5s ease-in-out;
	}
</style>
