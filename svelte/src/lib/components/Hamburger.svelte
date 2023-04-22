<script>
	export let open = false;
	export let onClick = () => {
		open = !open;
	};
</script>

<button on:click={onClick} class:open aria-expanded={open} aria-label="toggle menu" id="hamburger">
	<span />
	<span />
	<span />
	<span />
</button>

<style>
	:root {
		--hamburger-width: 40px;
		--hamburger-height: calc(var(--hamburger-width) / 1.5);
	}
	button {
		background: none;
		border: none;
		padding: 0;
		font: inherit;
		cursor: pointer;
		outline: inherit;
	}

	#hamburger {
		width: var(--hamburger-width);
		height: var(--hamburger-height);
		position: relative;
		margin: 50px auto;
		transform: rotate(0deg);
		transition: 0.5s ease-in-out;
		cursor: pointer;
	}

	#hamburger span {
		display: block;
		position: absolute;
		height: calc(var(--hamburger-height) / 7);
		width: 100%;
		background: var(--text-color-secondary);
		border-radius: calc(var(--hamburger-height) / 5);
		opacity: 1;
		left: 0;
		transform: rotate(0deg);
		transition: 0.25s ease-in-out;
	}
	#hamburger:hover span,
	:global(#hamburger.open) span {
		background: var(--color-svelte);
		filter: brightness(0.8);
	}

	#hamburger span:nth-child(1) {
		top: 0px;
	}

	#hamburger span:nth-child(2),
	#hamburger span:nth-child(3) {
		top: calc(var(--hamburger-height) / 3);
	}

	#hamburger span:nth-child(4) {
		top: calc(var(--hamburger-height) / 3 * 2);
	}

	:global(#hamburger.open) span:nth-child(1) {
		top: calc(var(--hamburger-height) / 3);
		width: 0%;
		left: 50%;
	}

	:global(#hamburger.open) span:nth-child(2) {
		transform: rotate(45deg);
	}

	:global(#hamburger.open) span:nth-child(3) {
		transform: rotate(-45deg);
	}

	:global(#hamburger.open) span:nth-child(4) {
		top: calc(var(--hamburger-height) / 3);
		width: 0%;
		left: 50%;
	}
</style>
