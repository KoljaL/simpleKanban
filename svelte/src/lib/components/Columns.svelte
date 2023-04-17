<script>
	// import Plus from '$lib/icons/Plus.svelte';
	export let columns;
	console.log(columns);

	let sliderColumns;
	let sliderHandle;
	let isDown = false;
	let startX;
	let scrollLeft;

	function onMouseDown(e) {
		isDown = true;
		sliderColumns.classList.add('active');
		startX = e.pageX - sliderColumns.offsetLeft;
		scrollLeft = sliderColumns.scrollLeft;
	}
	function onMouseLeave() {
		isDown = false;
		sliderColumns.classList.remove('active');
	}
	function onMouseUp() {
		isDown = false;
		sliderColumns.classList.remove('active');
	}
	function onMouseMove(e) {
		if (!isDown) return;
		e.preventDefault();
		const x = e.pageX - sliderColumns.offsetLeft;
		const walk = x - startX;
		sliderColumns.scrollLeft = scrollLeft - walk;
	}
</script>

<section class="columns" bind:this={sliderColumns}>
	{#each columns as column}
		<article class="column">
			<header class="column_header">
				<h2
					bind:this={sliderHandle}
					on:mousedown={onMouseDown}
					on:mouseleave={onMouseLeave}
					on:mouseup={onMouseUp}
					on:mousemove={onMouseMove}
				>
					{column.column_name}
				</h2>
				<!-- <Plus /> -->
			</header>
			<ul class="column_content">
				<li class="task">
					<h3>anme</h3>
				</li>
			</ul>
			<footer class="column_footer">
				<button class="add_task">Add Task</button>
			</footer>
		</article>
	{/each}
</section>

<style>
	.columns {
		height: 100%;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		gap: 1rem;
		overflow-x: auto;
		transition: all 0.2s;
	}
	.columns:global(.active) {
		transform: scale(1);
	}

	.column {
		min-width: 300px;
		max-width: 100%;
		min-height: 0;
		max-height: 100%;
		margin: 0;
		padding: 0;
		border: 1px solid var(--v-lightDark);
		--border-radius-column: 0.5rem;
		border-radius: var(--border-radius-column);
	}
	.column_header {
		position: relative;
		background-color: var(--bg-color-secondary);
		border-top-left-radius: var(--border-radius-column);
		border-top-right-radius: var(--border-radius-column);
	}

	.column_header h2 {
		margin-block: 0rem;
		padding-block: 0.25rem;
		text-align: center;
		-webkit-user-select: none; /* Safari */
		-ms-user-select: none; /* IE 10 and IE 11 */
		user-select: none; /* Standard syntax */
		cursor: grab;
	}
	.columns:global(.active) .column_header h2 {
		cursor: grabbing;
		cursor: -webkit-grabbing;
	}
	.column_footer {
		position: relative;
		padding: 0.25rem;
		padding-bottom: 0rem;
		margin-bottom: 0;
		margin: 0;
		background-color: var(--bg-color-secondary);
		border-bottom-left-radius: var(--border-radius-column);
		border-bottom-right-radius: var(--border-radius-column);
	}
</style>
