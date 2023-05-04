<script>
	// import onMount from 'svelte';
	import { fade } from 'svelte/transition';
	import { clickOutside } from '$lib/utils.js';
	import Skanban from '$lib/icons/Skanban.svelte';
	// import { customLayout } from '$lib/store.js';
	// import Github from '$lib/icons/Github.svelte';
	import Typewriter from 'svelte-typewriter';
	import Menu from '$lib/components/Menu.svelte';
	import Hamburger from '$lib/components/Hamburger.svelte';
	import AddColumn from '$lib/icons/AddColumn.svelte';
	// console.log('$customLayout Header.svelte', $customLayout);
	export let open = false;
	open = true;
	export let openMenu = () => {
		open = !open;
	};
	// $: console.log(open);
</script>

<header>
	<div class="header_left">
		<div class="dbKey">
			<!-- {$dbKey} -->
		</div>
	</div>

	<div class="header_center">
		<div class="pagename">
			<!-- <Skanban /> -->
			<Typewriter interval={100} cursor={false}>Skanban</Typewriter>
		</div>
	</div>

	<div class="header_right" use:clickOutside on:click_outside={() => (open = false)}>
		<Hamburger {open} {openMenu} />
		{#if open}
			<nav class="navigation boxShadow" transition:fade={{ duration: 100 }}>
				<Menu />
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
		width: 120px;
		font-size: 1.75rem;
		color: var(--color-svelte);
	}
	/* .pagename :global(svg) {
		transform: scale(0.5) translateY(0.5rem);
	} */

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
		background-color: var(--bg-color-tertiary);
		/* border: 1px solid var(--border-color); */
		border-radius: var(--border-radius-m);
	}
</style>
