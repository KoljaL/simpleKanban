<script>
	import { browser } from '$app/environment';
	import ThemeToggle from '$lib/components/ThemeToggle.svelte';
	import { fade } from 'svelte/transition';
	import { clickOutside } from '$lib/utils.js';

	import Hamburger from '$lib/components/Hamburger.svelte';
	export let open = false;
	export let onClick = () => {
		open = !open;
	};
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
	<div class="header_right">
		<Hamburger {open} {onClick} />
		{#if open}
			<nav
				transition:fade={{ duration: 200 }}
				use:clickOutside
				on:click_outside={() => (open = false)}
			>
				<!-- <ThemeToggle /> -->
				<ul>
					<li>
						<button class="styleLessButton">new Column</button>
					</li>
					<li>
						<button class="styleLessButton">toggle Theme</button>
					</li>
					<li>
						<button class="styleLessButton">...</button>
					</li>
				</ul>
			</nav>
		{/if}
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
		position: relative;
	}

	nav {
		position: absolute;
		top: 5rem;
		right: 0;
		z-index: 10;
		width: max-content;
		padding: 0.25rem 1rem;
		background-color: var(--bg-color-secondary);
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-m);
	}
	ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	li {
		margin: 0.5rem 0;
	}
	button {
		color: var(--text-color-secondary);
		transition: all 0.2s ease-in-out;
	}

	button:hover {
		color: var(--color-svelte);
	}
</style>
