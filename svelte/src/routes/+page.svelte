<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { flip } from 'svelte/animate';
	import { onMount } from 'svelte';
	import { dndzone, SOURCES, TRIGGERS } from 'svelte-dnd-action';
	import { topicStore } from '$lib/store.js';
	// components
	import Topics from '$lib/components/Topics.svelte';
	import NewTopic from '$lib/components/NewTopic.svelte';
	// icons
	import MoveH from '$lib/icons/MoveH.svelte';
	// get data from page.js and add it to the store
	export let data;
	console.log('data', data);
	$topicStore = data.data.columns;
	// define variables
	const flipDurationMs = 200;
	let sliderColumns;
	let sliderHandle;
	let isDown = false;
	let startX;
	let scrollLeft;
	let draggedColumn; // maybe obsolete
	let dragColumnDisabled = true;

	onMount(() => {
		getColumnPositions();
	});

	/**
	 * @description get the positions of the columns and topics
	 * @returns {array} columnPositions
	 */
	function getColumnPositions() {
		let columnPositions = [];
		$topicStore.forEach((element) => {
			columnPositions.push({ id: element.id, position: element.position });
		});
		// console.log('columnPositions', columnPositions);
		return columnPositions;
	}

	function columnSliderOnMouseDown(e) {
		isDown = true;
		sliderColumns.classList.add('active');
		startX = e.pageX - sliderColumns.offsetLeft;
		scrollLeft = sliderColumns.scrollLeft;
	}
	function columnSliderOnMouseLeave() {
		isDown = false;
		sliderColumns.classList.remove('active');
	}
	function columnSliderOnMouseUp() {
		isDown = false;
		sliderColumns.classList.remove('active');
	}
	function columnSliderOnMouseMove(e) {
		if (!isDown) return;
		e.preventDefault();
		const x = e.pageX - sliderColumns.offsetLeft;
		const walk = x - startX;
		sliderColumns.scrollLeft = scrollLeft - walk;
	}

	//
	//
	// DRAG&DROP COLUMNS
	//
	//

	function handleDragColumn(e, draggedElement) {
		const {
			items: newItems,
			info: { source, trigger }
		} = e.detail;
		$topicStore = e.detail.items;
		if (source === SOURCES.KEYBOARD && trigger === TRIGGERS.DRAG_STOPPED) {
			dragColumnDisabled = true;
		}
	}
	function handleDropColumn(e, draggedElement) {
		const {
			items: newItems,
			info: { source }
		} = e.detail;
		// console.log('handleDropColumn');
		// update position of dragged column by index
		let columns = e.detail.items;
		columns.forEach((c, i) => {
			c.position = i;
		});
		// console.log('DROP id', e.detail.info.id);
		// console.log('columns after', columns);
		// update store
		$topicStore = columns;

		// update position in db
		fetch(PUBLIC_API_URL + 'updatecolumnpositions', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(getColumnPositions())
		})
			.then((res) => res.json())
			.then((data) => {
				// console.log('data', data);
			});
		if (source === SOURCES.POINTER) {
			dragColumnDisabled = true;
		}
	}

	// https://svelte.dev/repl/61a0549c05bd45369134213d57bfd4a6?version=3.58.0
	function startDragByHandle(e) {
		e.preventDefault();
		dragColumnDisabled = false;
	}
	function handleKeyDownByHandle(e) {
		if ((e.key === 'Enter' || e.key === ' ') && dragColumnDisabled) dragColumnDisabled = false;
	}

	//
	// REACTIVE CONSOLE OUTPUT
	//
	// $: console.log('$columnPositions', columnPositions);
	// $: console.log('dragColumnDisabled', dragColumnDisabled);
	// $: console.log('$topicStore page.svelte', $topicStore);
</script>

<!-- {#if $topicStore}
	<pre>
	{JSON.stringify($topicStore, null, 2)}
</pre>
{/if} -->
{#if $topicStore}
	<section
		class="columns"
		bind:this={sliderColumns}
		use:dndzone={{
			items: $topicStore,
			dragDisabled: dragColumnDisabled,
			flipDurationMs,
			type: 'columns',
			dropTargetStyle: {
				outline: 'rgba(255, 64, 0, 0.5) solid 2px',
				outlineOffset: '2px',
				'border-radius': '5px'
			}
		}}
		on:consider={(e) => handleDragColumn(e, draggedColumn)}
		on:finalize={(e) => handleDropColumn(e, draggedColumn)}
	>
		{#each $topicStore as column (column.id)}
			<article
				class="column"
				id="column_{column.id}"
				bind:this={draggedColumn}
				animate:flip={{ duration: flipDurationMs }}
			>
				<header class="column_header">
					<div
						aria-label="drag-handle"
						class="dragHandle"
						style={dragColumnDisabled ? 'cursor: grab' : 'cursor: grabbing'}
						on:mousedown={startDragByHandle}
						on:touchstart={startDragByHandle}
						on:keydown={handleKeyDownByHandle}
					>
						<MoveH />
					</div>
					<h2
						bind:this={sliderHandle}
						on:mousedown={columnSliderOnMouseDown}
						on:mouseleave={columnSliderOnMouseLeave}
						on:mouseup={columnSliderOnMouseUp}
						on:mousemove={columnSliderOnMouseMove}
						style="color:{column.color}"
					>
						{column.column_name}
					</h2>
					<div class="newTopic">
						<NewTopic columnId={column.id} columns={$topicStore} />
					</div>
				</header>

				<Topics topics={column.topics} columnId={column.id} />
			</article>
		{/each}
	</section>
{/if}

<style>
	.columns {
		height: 100%;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		gap: 1rem;
		padding-inline: 0.5rem;
		overflow-x: auto;
		transition: all 0.2s;
	}
	.columns:global(.active) {
		transform: scale(1);
	}

	.column {
		min-width: var(--min-width-column);
		max-width: 95vw;
		width: 10rem;
		min-height: 0;
		max-height: 100%;
		margin: 0;
		padding: 0;
		transition: width 0.5s ease-in-out, min-width 0.5s ease-in-out;

		/* border: 1px solid var(--color-border);
		--border-radius-column: 0.5rem;
		border-radius: var(--border-radius-column); */
	}
	.column_header {
		position: relative;
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		padding-inline: 0.5rem;
		/* background-color: var(--bg-color-secondary);
		border-top-left-radius: var(--border-radius-column);
		border-top-right-radius: var(--border-radius-column); */
	}

	.dragHandle :global(path) {
		stroke: var(--color-text-secondary);
		filter: brightness(0.5);
		opacity: 0.2;
		transition: all 0.2s;
	}
	.column_header:hover .dragHandle :global(path) {
		opacity: 1;
	}
	.dragHandle:hover :global(path) {
		stroke: var(--color-text);
		filter: brightness(1);
	}

	.column_header h2 {
		margin-block: 0rem;
		padding-block: 0.25rem;
		text-align: center;
		-webkit-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: grab;
		transition: all 0.2s;
		color: var(--color-text-secondary);
	}

	.newTopic :global(svg) {
		filter: brightness(0.7);
	}
	.columns:global(.active) .column_header h2 {
		filter: brightness(1.4);
		cursor: grabbing;
		cursor: -webkit-grabbing;
	}
</style>
