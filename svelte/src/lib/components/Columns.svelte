<script>
	import Plus from '$lib/icons/Plus.svelte';
	export let columns;
	console.log(columns);

	let slider;
	let isDown = false;
	let startX;
	let scrollLeft;

	slider.addEventListener('mousedown', (e) => {
		isDown = true;
		slider.classList.add('active');
		startX = e.pageX - slider.offsetLeft;
		scrollLeft = slider.scrollLeft;
	});
	slider.addEventListener('mouseleave', () => {
		isDown = false;
		slider.classList.remove('active');
	});
	slider.addEventListener('mouseup', () => {
		isDown = false;
		slider.classList.remove('active');
	});
	slider.addEventListener('mousemove', (e) => {
		if (!isDown) return;
		e.preventDefault();
		const x = e.pageX - slider.offsetLeft;
		const walk = (x - startX) * 3; //scroll-fast
		slider.scrollLeft = scrollLeft - walk;
		console.log(walk);
	});
</script>

<section class="columns" bind:{slider}>
	{#each columns as column}
		<article class="column">
			<header class="column_header">
				<h2>{column.column_name}</h2>
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
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		gap: 1rem;
		overflow-x: auto;
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
