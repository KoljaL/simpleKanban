<script>
	import { onMount } from 'svelte';
	import Header from '$lib/layout/Header.svelte';
	import Footer from '$lib/layout/Footer.svelte';
	import ScrollArea from '$lib/components/ScrollArea.svelte';
	import { layoutCustomisation } from '$lib/store.js';

	$layoutCustomisation = {
		maxWidthPage: 70,
		minWidthColumn: 20
	};

	onMount(() => {
		$layoutCustomisation.maxWidthPage =
			localStorage.getItem('Skanban-maxWidthPage') || $layoutCustomisation.maxWidthPage;
		$layoutCustomisation.minWidthColumn =
			localStorage.getItem('Skanban-minWidthColumn') || $layoutCustomisation.minWidthColumn;
		console.log('onMount');
		// console.log($layoutCustomisation.maxWidthPage);
		// console.log($layoutCustomisation.minWidthColumn);
	});

	// import '$lib/styles/pico.css';
	// import '$lib/styles/ui.css';
	// import '$lib/styles/tooltip.css';
	import '$lib/styles/styles.css';
</script>

<svelte:head>
	{@html `
  <style>
    :root {
      --max-width-page: ${$layoutCustomisation.maxWidthPage}rem;
      --min-width-column: ${$layoutCustomisation.minWidthColumn}rem;
    }
    </style>
    `}
</svelte:head>

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
