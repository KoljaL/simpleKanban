<script>
	import { browser } from '$app/environment';
	import ThemeToggle from '$lib/components/ThemeToggle.svelte';
	function colorCallback(rgba) {
		console.log(rgba.detail);
	}
</script>

<!-- {#if browser}

{/if} -->
<svelte:head>
	<title>Skanban</title>
	<meta name="description" content="Svelte demo app" />
	<!-- <link rel="preload" href="./font/Overpass-Light.woff2" as="font" crossOrigin="anonymous" />
	<link
		rel="preload"
		href="$lib/styles/font/Overpass-Light.woff2"
		as="font"
		type="font/woff2"
		crossorigin
	/> -->
</svelte:head>

<header>
	<div class="header_left" />
	<div class="header_center">
		<span class="pagename"> Skanban </span>
	</div>
	<div class="header_right">
		<ThemeToggle />
	</div>
</header>

<style>
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.pagename {
		font-size: 1.75rem;
	}

	.header_right {
	}
</style>
