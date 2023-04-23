<script>
	import { onMount } from 'svelte';

	let scrollLeft;
	let scrollRight;
	let scrollLeftActive = false;
	let scrollRightActive = false;
	let scrollIntervallTimer;
	let scrollStep = 5;
	let scrollInervallDuration = 10;

	onMount(() => {
		handleScroll();
	});

	function handleScrollRight(e) {
		let contentElement = e.target.previousElementSibling;
		scrollIntervallTimer = setInterval(() => {
			contentElement.scrollLeft += scrollStep;
			if (contentElement.scrollLeft >= contentElement.scrollWidth - contentElement.clientWidth) {
				handleScrollStop();
			}
		}, scrollInervallDuration);
	}

	function handleScrollLeft(e) {
		let contentElement = e.target.nextElementSibling;
		scrollIntervallTimer = setInterval(() => {
			contentElement.scrollLeft -= scrollStep;
			if (contentElement.scrollLeft <= 0) {
				handleScrollStop();
			}
		}, scrollInervallDuration);
	}

	function handleScrollStop() {
		clearInterval(scrollIntervallTimer);
		handleScroll();
	}

	function handleScroll() {
		let contentElement = scrollLeft.nextElementSibling;
		if (contentElement.scrollLeft >= contentElement.scrollWidth - contentElement.clientWidth) {
			scrollRightActive = false;
		} else {
			scrollRightActive = true;
		}
		if (contentElement.scrollLeft <= 0) {
			scrollLeftActive = false;
		} else {
			scrollLeftActive = true;
		}
	}
</script>

<div
	class="scrollElement scrollLeft"
	class:active={scrollLeftActive}
	bind:this={scrollLeft}
	on:mousedown={handleScrollLeft}
	on:keydown={handleScrollLeft}
	on:mouseup={handleScrollStop}
	on:keyup={handleScrollStop}
/>
<slot />
<div
	class="scrollElement scrollRight"
	class:active={scrollRightActive}
	bind:this={scrollRight}
	on:mousedown={handleScrollRight}
	on:keydown={handleScrollRight}
	on:mouseup={handleScrollStop}
	on:keyup={handleScrollStop}
/>

<style>
	.scrollElement {
		--element-width: 2.5rem;
		position: absolute;
		height: 100%;
		width: var(--element-width);
		background-color: var(--bg-color-secondary);
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-m);
		opacity: 0;
		transition: opacity var(--duration-hover) ease-in-out;
	}
	.scrollElement:global(.active) {
		opacity: 0.2;
	}
	.scrollElement:hover:global(.active) {
		opacity: 0.8;
	}

	.scrollRight.active {
		right: calc(var(--element-width) * -1);
		cursor: e-resize;
	}
	.scrollLeft.active {
		left: calc(var(--element-width) * -1);
		cursor: w-resize;
	}
</style>
