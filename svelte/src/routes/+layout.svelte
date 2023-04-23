<script>
	import { onMount } from 'svelte';
	import Header from '$lib/layout/Header.svelte';
	import Footer from '$lib/layout/Footer.svelte';
	import ScrollArea from '$lib/components/ScrollArea.svelte';
	// import '$lib/styles/pico.css';
	// import '$lib/styles/ui.css';
	// import '$lib/styles/tooltip.css';
	import '$lib/styles/styles.css';
</script>

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
		max-width: var(--max-width);
		height: var(--header-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-svelte);
	}

	:global(#SKA) > main {
		display: flex;
		width: 100%;
		max-width: var(--max-width);
		height: var(--main-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
	}

	:global(#SKA > footer) {
		width: 100%;
		max-width: var(--max-width);
		height: var(--footer-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-text);
	}
</style>
