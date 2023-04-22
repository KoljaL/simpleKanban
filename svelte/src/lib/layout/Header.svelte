<script>
	import { browser } from '$app/environment';
	import ThemeToggle from '$lib/components/ThemeToggle.svelte';
	import { fade } from 'svelte/transition';
	import { clickOutside } from '$lib/utils.js';
	import Github from '$lib/icons/Github.svelte';
	import Hamburger from '$lib/components/Hamburger.svelte';
	export let open = false;
	export let onClick = () => {
		console.log('click', open);
		open = !open;
	};
</script>

<svelte:head>
	<title>Skanban</title>
	<meta name="description" content="Simple Kanban task management" />
	<script>
		var dataTheme;
		dataTheme = window.localStorage.getItem('Skanban-theme') || '';
		if (!dataTheme) {
			dataTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
		}
		document.documentElement.setAttribute('data-theme', dataTheme);
	</script>
</svelte:head>

<header>
	<div class="header_left" />
	<div class="header_center">
		<span class="pagename"> Skanban </span>
	</div>
	<div class="header_right" use:clickOutside on:click_outside={() => (open = false)}>
		<Hamburger {open} {onClick} />
		{#if open}
			<nav transition:fade={{ duration: 200 }}>
				<ul>
					<li>
						<button class="styleLessButton">new Column</button>
					</li>
					<li>
						<button class="styleLessButton">...</button>
					</li>
					<li class="bottom">
						<a class="ghLink" href="http://" target="_blank" rel="noopener noreferrer"><Github /></a
						>
						<ThemeToggle />
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
		transition: color var(--duration-hover) ease-in-out;
	}

	button:hover {
		color: var(--color-svelte);
	}

	.bottom {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-top: auto;
		margin-inline: -1rem;
		margin-bottom: -0.2rem;
		padding-inline: 0.5rem;
		/* background-color: var(--bg-color-primary);
		border-bottom-left-radius: var(--border-radius-m);
		border-bottom-right-radius: var(--border-radius-m); */
	}
	.bottom > * {
		height: 2rem;
		margin-inline: 0.5rem;
	}

	.ghLink :global(svg) {
		margin-top: 0.35rem;
		width: 1.25rem;
		height: 1.25rem;
		fill: var(--text-color-secondary);
		transition: fill var(--duration-hover) ease-in-out;
	}

	.ghLink:hover {
		filter: none;
	}
	.ghLink:hover :global(svg) {
		filter: none;
		fill: var(--color-svelte);
	}
</style>
