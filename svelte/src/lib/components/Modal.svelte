<script>
	export let openModal = false;
	export let position;
	import { onDestroy, createEventDispatcher } from 'svelte';
	import { isModalOpen } from '$lib/store.js';

	import { fade } from 'svelte/transition';
	import { cubicOut } from 'svelte/easing';
	import Close from '$lib/icons/Close.svelte';
	import Expand from '$lib/icons/Expand.svelte';
	import Shrink from '$lib/icons/Shrink.svelte';
	onDestroy(() => {
		openModal = false;
		expanded = false;
	});
	const dispatch = createEventDispatcher();
	let clicked = false;
	let scrolled = false;
	let canClose = true;
	let wasOpen = true;
	let innerWidth = 0;
	let modalWidth = 0;
	let showShrink = true;
	export let expanded = false;
	if (expanded) {
		showShrink = false;
	}
	$: modalWidth;
	$: handleOpen(openModal);

	function handleOpen() {
		if (openModal) {
			wasOpen = document?.body?.classList?.contains('modal-open');
			if (!wasOpen) {
				document?.body?.classList?.add('modal-open');
			}
			dispatch('open');
		} else {
			if (!wasOpen) {
				document?.body?.classList?.remove('modal-open');
			}
			dispatch('close');
		}
	}
	function close(force = false) {
		console.log('close', force, canClose);
		if (force || canClose) {
			openModal = false;
			isModalOpen.set(false);
			expanded = false;
		}
	}
	function handleEscape(e) {
		if (openModal && e.key === 'Escape') {
			close();
		}
	}
	function handleMouseDown() {
		if (openModal) {
			clicked = true;
		}
	}
	function handleMouseUp() {
		canClose = !scrolled;
		clicked = false;
		scrolled = false;
		close();
	}
	function handleScroll() {
		if (clicked) {
			scrolled = true;
		}
	}

	if (position) {
		setTimeout(() => {
			let posY = position.y + 32;
			let posX = position.x - modalWidth / 2;
			if (posX < 0) posX = 16;
			if (posX + modalWidth > innerWidth) posX = innerWidth - modalWidth - 16;
			position = `top: ${posY}px; left: ${posX}px;`;
		}, 1);
	}
</script>

<svelte:window on:keyup={handleEscape} on:mousedown={handleMouseDown} bind:innerWidth />
{#if openModal}
	<div
		id="modal-background"
		on:mouseup|self={handleMouseUp}
		on:scroll={handleScroll}
		transition:fade={{ duration: 200 }}
	>
		<div
			id="modal-container"
			class="boxShadow"
			class:expanded
			style={position}
			bind:clientWidth={modalWidth}
			on:mousedown|stopPropagation={() => (canClose = false)}
			transition:fade={{ y: 500, easing: cubicOut, duration: 400 }}
		>
			<div class="modal-header">
				<button
					class="styleLessButton expand-modal-button"
					title="expand Modal"
					on:click={() => {
						expanded = !expanded;
					}}
					on:keyup={() => {
						expanded = !expanded;
					}}
				>
					{#if expanded && showShrink}
						<Shrink />
					{:else if showShrink}
						<Expand />
					{/if}
				</button>
				<button
					class="styleLessButton close-modal-button"
					title="close Modal"
					on:click={() => close(true)}
					on:keyup={() => close(true)}
				>
					<Close />
				</button>
			</div>
			<div>
				<slot />
			</div>
		</div>
	</div>
{/if}

<style>
	:global(.modal-open) {
		overflow: hidden;
	}
	#modal-background {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999;
		height: 100%;
		width: 100%;
		overflow-y: auto;
		background: rgba(0, 0, 0, 0.5);
	}
	#modal-container {
		position: relative;
		/* top: 30%;
		left: 50%; */
		/* transform: translate(-50%); */
		/* margin-top: 1rem; */
		/* border: 1px solid var(--border-color); */
		/* box-shadow: var(--shadow-4); */
		/* background: var(--bg-color-primary); */
		border-radius: var(--border-radius-m);
		padding: 0.75rem;
		padding-top: 0;
		width: 370px;
		max-width: 90vw;
		max-height: 90vh;
		transition: all 0.3s ease-in-out;
	}
	#modal-container.expanded {
		top: 2rem !important;
		left: 2rem !important;
		width: calc(60vw - 2rem);
		height: calc(100% - 2rem);
		transform: translate(30%);
	}

	.modal-header {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0.5rem;
		display: flex;
		justify-content: space-between;
		gap: 0.5rem;
		align-items: center;
	}
	.close-modal-button {
	}
</style>
