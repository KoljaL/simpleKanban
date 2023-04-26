<script>
	import { onMount } from 'svelte';
	import { layoutCustomisation } from '$lib/store.js';

	// $: maxWidthPage = $layoutCustomisation.maxWidthPage;
	// $: minWidthColumn = $layoutCustomisation.minWidthColumn;

	$: $layoutCustomisation.maxWidthPage;
	$: $layoutCustomisation.minWidthColumn;

	// let maxWidthPage;
	// let minWidthColumn;
	// $: $layoutCustomisation = {
	// 	maxWidthPage,
	// 	minWidthColumn
	// };

	const selectPageWidths = {
		small: '70',
		medium: '80',
		large: '90',
		xlarge: '100'
	};

	const selectColumnWidths = {
		small: '10',
		medium: '20',
		large: '30',
		xlarge: '40'
	};

	// onMount(() => {
	// 	maxWidthPage = localStorage.getItem('Skanban-maxWidthPage') || maxWidthPage;
	// 	minWidthColumn = localStorage.getItem('Skanban-minWidthColumn') || minWidthColumn;
	// 	console.log('onMount');
	// 	// console.log(maxWidthPage);
	// 	// console.log(minWidthColumn);
	// });

	function setWidth(e) {
		let name = e.target.name;
		let value = e.target.value;

		switch (name) {
			case 'maxWidthPage':
				$layoutCustomisation.maxWidthPage = value;
				break;
			case 'minWidthColumn':
				$layoutCustomisation.minWidthColumn = value;
				break;
		}
		console.log('storeLocal', e.target.parentElement.name);
		localStorage.setItem('Skanban-' + e.target.parentElement.name, e.target.value);
		console.log('Skanban-' + e.target.name, e.target.value);
	}
</script>

<!-- <svelte:head>
	{@html `
  <style>
    :root {
      --max-width-page: ${maxWidthPage}rem;
      --min-width-column: ${minWidthColumn}rem;
    }
    </style>
    `}
</svelte:head> -->

<li class="rangeInputs">
	<label>
		<span>Column width</span>
		<!-- bind:value={minWidthColumn} -->
		<select name="minWidthColumn" on:change={setWidth}>
			{#each Object.keys(selectColumnWidths) as key}
				<option
					value={selectColumnWidths[key]}
					selected={selectColumnWidths[key] === $layoutCustomisation.minWidthColumn}>{key}</option
				>
			{/each}
		</select>
		<!-- <input
			type="range"
			name="minWidthColumn"
			min="10"
			max="30"
			step="1"
			bind:value={minWidthColumn}
			on:input={setWidth}
			on:mouseup={storeLocal}
			on:blur={storeLocal}
		/> -->
	</label>
	<!-- on:mouseup={storeLocal} on:blur={storeLocal} -->
	<label>
		<span>Page width</span>
		<select name="maxWidthPage" on:change={setWidth}>
			{#each Object.keys(selectPageWidths) as key}
				<option
					value={selectPageWidths[key]}
					selected={selectPageWidths[key] === $layoutCustomisation.maxWidthPage}>{key}</option
				>
			{/each}
		</select>
	</label>
</li>

<style>
	.rangeInputs {
		display: flex;
		flex-direction: column;
		align-items: start;
	}
	.rangeInputs label {
		width: 100%;
		display: flex;
		align-items: baseline;
		justify-content: space-between;
		font-size: 0.9rem;
		gap: 1rem;
		margin-bottom: 1rem;
		color: var(--color-text-secondary);
	}
</style>
