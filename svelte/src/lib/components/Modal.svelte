<script>
	export let open = false;
	import { onDestroy, createEventDispatcher } from 'svelte';
	import { fade } from 'svelte/transition';
	import { cubicOut } from 'svelte/easing';
	import { BROWSER } from 'esm-env';
	// import Portal from '$components/portal.svelte';
	onDestroy(() => {
		open = false;
	});
	const dispatch = createEventDispatcher();
	let clicked = false;
	let scrolled = false;
	let canClose = true;
	let wasOpen = true;
	$: handleOpen(open);
	function handleOpen() {
		if (!BROWSER) {
			return;
		}
		if (open) {
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
		if (force || canClose) {
			open = false;
		}
	}
	function handleEscape(e) {
		if (open && e.key === 'Escape') {
			close();
		}
	}
	function handleMouseDown() {
		if (open) {
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
</script>

<svelte:window on:keyup={handleEscape} on:mousedown={handleMouseDown} />
{#if open}
	<!-- <Portal> -->
	<div
		id="background"
		on:mouseup|self={handleMouseUp}
		on:scroll={handleScroll}
		transition:fade={{ duration: 200 }}
	>
		<div
			id="container"
			on:mousedown|stopPropagation={() => (canClose = false)}
			transition:fade={{ y: 500, easing: cubicOut, duration: 400 }}
		>
			<div id="title-container">
				<div id="title">
					<slot name="title" />
				</div>
				<div on:click={() => close(true)} on:keyup={() => close(true)}>
					<slot name="close">
						<div id="close">&times;</div>
					</slot>
				</div>
			</div>
			<div>
				<slot />
			</div>
			{#if $$slots.actions}
				<div id="actions">
					<slot name="actions" />
				</div>
			{/if}
		</div>
	</div>
	<!-- </Portal> -->
{/if}

<style>
	:global(.modal-open) {
		overflow: hidden;
	}
	#background {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999;
		display: grid;
		height: 100%;
		width: 100%;
		overflow-y: auto;
		background: rgba(0, 0, 0, 0.5);
		padding-bottom: 100px;
	}
	#container {
		margin-top: 1rem;
		place-self: start center;
		border-radius: var(--border-radius-m);
		background: var(--bg-color-primary);
		padding: 1rem;
		box-shadow: var(--box-shadow);
		width: max-content;
		max-width: 90vw;
	}

	#title-container {
		display: flex;
		align-items: center;
		margin: 0.75rem 0;
	}
	#title {
		flex: 1 1 auto;
		font-size: 1.2rem;
	}
	#close {
		cursor: pointer;
		font-size: 40px;
		line-height: 0.6;
	}
	#actions {
		display: flex;
		justify-content: flex-end;
		margin-top: 1rem;
		gap: 0.5rem;
	}
</style>
