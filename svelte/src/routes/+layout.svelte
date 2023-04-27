<script>
	// console.info('+layout.svelte');

	import { onMount } from 'svelte';
	import Header from '$lib/layout/Header.svelte';
	import Footer from '$lib/layout/Footer.svelte';
	import ScrollArea from '$lib/components/ScrollArea.svelte';
	import { customLayout, topicStore } from '$lib/store.js';
	import { setDebug } from '$lib/utils.js';

	import { page } from '$app/stores';
	import { Head } from 'svead';

	setDebug(0);

	$: $customLayout = $customLayout;

	$: deb.y('customLayout layout.sveslte', $customLayout);
	deb.y('topicStsore', $topicStore);

	let title = 'Skanban';
	let description = 'A Kanban made with Svelte';
	let url = $page.url.toString();
	$: deb.y('customLayout layout.svelte', $customLayout);

	// $dbHash = 'abc';
	// $customLayout = {
	// 	maxWidthPage: 70,
	// 	minWidthColumn: 20
	// };

	// onMount(() => {
	// 	$dbHash = localStorage.getItem('Skanban-dbHash') || 'abc';

	// 	$customLayout.maxWidthPage =
	// 		localStorage.getItem('Skanban-maxWidthPage') || $customLayout.maxWidthPage;
	// 	$customLayout.minWidthColumn =
	// 		localStorage.getItem('Skanban-minWidthColumn') || $customLayout.minWidthColumn;
	// 	console.log('onMount');
	// 	// console.log($customLayout.maxWidthPage);
	// 	// console.log($customLayout.minWidthColumn);
	// });

	// import '$lib/styles/pico.css';
	// import '$lib/styles/ui.css';
	// import '$lib/styles/tooltip.css';
	import '$lib/styles/styles.css';
</script>

<Head {title} {description} {url} />

<!-- <svelte:head>
	{@html `
  <style>
    :root {
      --max-width-page: ${$customLayout.maxWidthPage}rem;
      --min-width-column: ${$customLayout.minWidthColumn}rem;
    }
    </style>
    `}
</svelte:head> -->

<Header />
<main>
	<ScrollArea>
		<slot />
	</ScrollArea>
</main>

<Footer />

<style>
	:global(#SKA > header) {
		width: 100%;
		max-width: var(--max-width-page);
		height: var(--header-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-svelte);
		transition: width 0.5s ease-in-out, max-width 0.5s ease-in-out;
	}

	:global(#SKA) > main {
		position: relative;
		display: flex;
		width: 100%;
		max-width: var(--max-width-page);
		height: var(--main-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		transition: width 0.5s ease-in-out, max-width 0.5s ease-in-out;
	}

	:global(#SKA > footer) {
		width: 100%;
		max-width: var(--max-width-page);
		height: var(--footer-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-text);
		transition: width 0.5s ease-in-out, max-width 0.5s ease-in-out;
	}
</style>
