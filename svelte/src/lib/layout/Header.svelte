<script>
	import { page } from '$app/stores';
	import logo from '$lib/images/svelte-logo.svg';
	import github from '$lib/images/github.svg';
</script>

<svelte:head>
	<title>Skanban</title>
	<meta name="description" content="Svelte demo app" />
</svelte:head>

<header>
	<div class="header_left" />
	<div class="header_center">
		<span class="pagename"> Skanban </span>
	</div>
	<div class="header_right" />
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
</style>
