<script>
	import { topicStore } from '$lib/store.js';
	import Topics from '$lib/components/Topics.svelte';
	import NewTopic from '$lib/components/NewTopic.svelte';
	import { flip } from 'svelte/animate';
	import { dndzone } from 'svelte-dnd-action';
	export let data;
	import { setDebugMode } from 'svelte-dnd-action';
	// setDebugMode(true);
	$topicStore = data.data;
	// console.log(data.data);
	const flipDurationMs = 200;
	let sliderColumns;
	let sliderHandle;
	let isDown = false;
	let startX;
	let scrollLeft;
	let draggedColumn;
	const API = 'https://dev.rasal.de/skanban/api.php?';

	function getColumnPositions() {
		let columnPositions = [];
		$topicStore.forEach((element) => {
			columnPositions.push({ id: element.id, position: element.position });
		});
		console.log('columnPositions', columnPositions);
		return columnPositions;
	}
	let columnPositions = getColumnPositions();

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

	function handleDragColumn(e, draggedElement) {
		// console.log('consider columns', e);
		// console.log('DRAG el', draggedElement);
		// let draggedId = draggedElement.id.replace('column_', '');
		// console.log('DRAG id', draggedId);
		// console.log('DRAG id', e.detail.info.id);
		$topicStore = e.detail.items;
	}
	function handleDropColumn(e, draggedElement) {
		// let draggedId = draggedElement.id.replace('column_', '');
		console.log('handleDropColumn');
		// console.log(draggedColumn);
		// console.log('DROP columns', e);
		// console.log('DROP id', e.detail.info.id);
		// console.log('DROP el', draggedElement);
		// console.log('DROP id', draggedId);

		let columns = e.detail.items;
		console.log('columns before', columns);
		columns.forEach((c, i) => {
			c.position = i;
		});
		console.log('DROP id', e.detail.info.id);
		console.log('columns after', columns);
		$topicStore = columns;
		let newColumnPositions = getColumnPositions();

		fetch(API + 'updatecolumnpositions', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(newColumnPositions)
		})
			.then((res) => res.json())
			.then((data) => {
				console.log('data', data);
			});
	}
	function handleDragTopic(cid, e) {
		const colIdx = $topicStore.findIndex((c) => c.id === cid);
		console.log('Drag cid', cid);
		console.log('Drag colIdx', colIdx);
		$topicStore[colIdx].topic = e.detail.items;
		$topicStore = [...$topicStore];
	}
	function handleDropTopic(cid, e) {
		const colIdx = $topicStore.findIndex((c) => c.id === cid);
		console.log('Finalize colIdx', colIdx);
		$topicStore[colIdx].items = e.detail.items;
		$topicStore = [...$topicStore];
	}
	function handleClick(e) {
		// alert('dragabble elements are still clickable :)');
	}

	$: console.log('$columnPositions', columnPositions);
	$: console.log('$topicStore page.svelte', $topicStore);
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
		use:dndzone={{ items: $topicStore, flipDurationMs, type: 'columns' }}
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
					<h2
						bind:this={sliderHandle}
						on:mousedown={onMouseDown}
						on:mouseleave={onMouseLeave}
						on:mouseup={onMouseUp}
						on:mousemove={onMouseMove}
					>
						{column.column_name}
					</h2>
					<NewTopic columnId={column.id} columns={$topicStore} />
				</header>
				<ul
					class="column_content"
					use:dndzone={{ items: column.topics, flipDurationMs }}
					on:consider={(e) => handleDragTopic(column.id, e)}
					on:finalize={(e) => handleDropTopic(column.id, e)}
				>
					{#each column.topics as topic (topic.id)}
						<li on:click={handleClick} on:keydown={handleClick}>
							<Topics {topic} />
						</li>
					{/each}
				</ul>
			</article>
		{/each}
	</section>
{/if}

<!-- <Topics columnId={column.id} /> -->
<!-- <Topics topics={data.data.topics[column.id]} /> -->
<!-- <footer class="column_footer"><button class="add_task">Add Task</button></footer> -->

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
		-webkit-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: grab;
		transition: all 0.2s;
	}
	.columns:global(.active) .column_header h2 {
		filter: brightness(1.4);
		cursor: grabbing;
		cursor: -webkit-grabbing;
	}
	.column_content {
		list-style: none;
		padding-inline: 0.25rem;
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}
	/* .column_footer {
		position: relative;
		padding: 0.25rem;
		padding-bottom: 0rem;
		margin-bottom: 0;
		margin: 0;
		background-color: var(--bg-color-secondary);
		border-bottom-left-radius: var(--border-radius-column);
		border-bottom-right-radius: var(--border-radius-column);
	} */
</style>
