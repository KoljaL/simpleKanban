<script>
	// import onMount from 'svelte';
	import ThemeToggle from '$lib/components/ThemeToggle.svelte';
	import { fade } from 'svelte/transition';
	import { clickOutside } from '$lib/utils.js';
	import { layoutCustomisation } from '$lib/store.js';
	import Github from '$lib/icons/Github.svelte';
	import Typewriter from 'svelte-typewriter';
	import Delete from '$lib/icons/Delete.svelte';
	import LayoutCustomizer from '$lib/components/LayoutCustomizer.svelte';
	import Hamburger from '$lib/components/Hamburger.svelte';
	export let open = false;
	// $: $layoutCustomisation = $layoutCustomisation;
	open = true;
	export let openMenu = () => {
		open = !open;
	};
	// $: console.log(open);

	function deleteLocalStorage() {
		// remove all entries from local storage starts with Skanban-
		Object.keys(localStorage)
			.filter((key) => key.startsWith('Skanban-'))
			.forEach((key) => localStorage.removeItem(key));
	}
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
	<div class="header_left">
		<div class="dbKey">
			{$layoutCustomisation.dbKey}
		</div>
	</div>

	<div class="header_center">
		<span class="pagename">
			<Typewriter interval={50} cursor={false}>Skanban</Typewriter>
		</span>
	</div>

	<div class="header_right" use:clickOutside on:click_outside={() => (open = false)}>
		<Hamburger {open} {openMenu} />
		{#if open}
			<nav transition:fade={{ duration: 100 }}>
				<ul>
					<li>
						<button class="styleLessButton">new Column</button>
					</li>
					<li>
						<button
							class="styleLessButton"
							on:click={deleteLocalStorage}
							on:keydown={deleteLocalStorage}><Delete /> delete all Settings</button
						>
					</li>
					<LayoutCustomizer />
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
		top: 2rem;
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
		/* margin-top: 1rem; */
		margin-inline: -1rem;
		margin-bottom: 0.25rem;
		padding-top: 0.25rem;
		padding-left: 0.5rem;
		padding-right: 0.75rem;
		border-top: 1px solid var(--color-border);
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
		width: 1.5rem;
		height: 1.5rem;
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
