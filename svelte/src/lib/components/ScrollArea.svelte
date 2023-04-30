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
	on:mouseover={handleScrollLeft}
	on:focus={handleScrollLeft}
	on:mouseout={handleScrollStop}
	on:blur={handleScrollStop}
/>
<slot />
<div
	class="scrollElement scrollRight"
	class:active={scrollRightActive}
	bind:this={scrollRight}
	on:mouseover={handleScrollRight}
	on:focus={handleScrollRight}
	on:mouseout={handleScrollStop}
	on:blur={handleScrollStop}
/>

<style>
	.scrollElement {
		--element-width: 2.5rem;
		position: absolute;
		height: 100%;
		width: var(--element-width);
		background-color: var(--bg-color-secondary);
		border: 1px solid var(--border-color);
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

	.scrollRight {
		right: calc(var(--element-width) * -1 - 0.5rem);
	}
	.scrollLeft {
		left: calc(var(--element-width) * -1 - 0.5rem);
	}

	.scrollRight.active {
		cursor: e-resize;
	}
	.scrollLeft.active {
		cursor: w-resize;
	}
</style>
