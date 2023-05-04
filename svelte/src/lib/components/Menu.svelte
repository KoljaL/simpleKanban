<script>
	import { customLayout, topicStore } from '$lib/store.js';
	import Delete from '$lib/icons/Delete.svelte';
	import AddColumn from '$lib/icons/AddColumn.svelte';
	import ColumnWidth from '$lib/icons/ColumnWidth.svelte';
	import PageWidth from '$lib/icons/PageWidth.svelte';
	import NewColumn from '$lib/components/NewColumn.svelte';
	import Edit from '$lib/icons/Edit.svelte';
	import EditColumn from '$lib/components/EditColumn.svelte';
	var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';

	// $: $customLayout = $customLayout;
	let columnWidth = $customLayout.columnWidth || '15';
	let pageWidth = $customLayout.pageWidth || '50';
	$: $customLayout = {
		pageWidth,
		columnWidth
	};

	const selectPageWidths = {
		small: '30',
		medium: '50',
		large: '80',
		xlarge: '100'
	};

	const selectColumnWidths = {
		small: '10',
		medium: '15',
		large: '20',
		xlarge: '25'
	};

	function storeLocal() {
		let customLayout = {
			pageWidth: pageWidth,
			columnWidth: columnWidth
		};
		// console.log('customLayout', customLayout);
		localStorage.setItem('Skanban-customLayout', JSON.stringify(customLayout));
	}

	/**
	 * @description delete all localStorage entries that start with 'Skanban-'
	 *
	 * @returns {void}
	 */
	function deleteLocalStorage() {
		console.log('deleteLocalStorage');
		columnWidth = '15';
		pageWidth = '50';
		Object.keys(localStorage)
			.filter((key) => key.startsWith('Skanban-'))
			.forEach((key) => {
				if (key === 'Skanban-dbKeys') return;
				localStorage.removeItem(key);
			});
	}

	function toggleTheme(e) {
		console.log('toggleTheme', e.target);
		currentTheme = e.target.value;
		document.documentElement.setAttribute('data-theme', currentTheme);
		localStorage.setItem('Skanban-theme', currentTheme);
	}
</script>

<ul>
	<li class="menuListItem">
		<label class="labelWithIcon" for="addNewColumn">
			<AddColumn />
			<span>new Column</span>
		</label>
		<NewColumn columnId={1} columns={$topicStore} />
	</li>

	<li class="menuListItem">
		<label class="labelWithIcon" for="addNewColumn">
			<Edit />
			<span>edit Column</span>
		</label>
		<EditColumn />
	</li>

	<li class="menuListItem">
		<label class="labelWithIcon" for="addNewColumn">
			<Delete />
			<span>remove Column</span>
		</label>
		<EditColumn columnId={1} columns={$topicStore} />
	</li>

	<hr />

	<li class="menuListItem">
		<label class="labelWithIcon" for="pageWidth">
			<PageWidth />
			<span>Page width</span>
		</label>
		<select name="pageWidth" bind:value={pageWidth} on:change={storeLocal}>
			{#each Object.keys(selectPageWidths) as key}
				<option
					value={selectPageWidths[key]}
					selected={selectPageWidths[key] === $customLayout.pageWidth}
				>
					{key}
				</option>
			{/each}
		</select>
	</li>

	<li class="menuListItem">
		<label class="labelWithIcon" for="columnWidth">
			<ColumnWidth />
			<span>Column width</span>
		</label>

		<select name="columnWidth" bind:value={columnWidth} on:change={storeLocal}>
			{#each Object.keys(selectColumnWidths) as key}
				<option
					value={selectColumnWidths[key]}
					selected={selectColumnWidths[key] === $customLayout.columnWidth}
				>
					{key}
				</option>
			{/each}
		</select>
	</li>

	<li class="menuListItem">
		<label class="labelWithIcon themeToggle" for="themeToggle">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				aria-hidden="true"
				width="2rem"
				height="2rem"
				fill="currentColor"
				stroke-linecap="round"
				class="sun_to_moon theme-toggle"
				viewBox="0 0 32 32"
			>
				<clipPath id="sun_to_moon__cutout">
					<path d="M0-5h30a1 1 0 0 0 9 13v24H0Z" />
				</clipPath>
				<g clip-path="url(#sun_to_moon__cutout)">
					<circle cx="16" cy="16" r="9.34" />
					<g stroke="currentColor" stroke-width="1.5">
						<path d="M16 5.5v-4" />
						<path d="M16 30.5v-4" />
						<path d="M1.5 16h4" />
						<path d="M26.5 16h4" />
						<path d="m23.4 8.6 2.8-2.8" />
						<path d="m5.7 26.3 2.9-2.9" />
						<path d="m5.8 5.8 2.8 2.8" />
						<path d="m23.4 23.4 2.9 2.9" />
					</g>
				</g>
			</svg>

			<span>Theme Color</span>
		</label>

		<select name="themeToggle" on:change={toggleTheme}>
			<option value="light" selected={currentTheme === 'light'}>Light</option>
			<option value="dark" selected={currentTheme === 'dark'}>Dark</option>
		</select>
	</li>

	<li class="menuListItem">
		<label class="labelWithIcon" for="deleteLocalStorage">
			<Delete />
			<span>Layout</span>
		</label>
		<button
			class="ButtonWithIcon removeAllSettings"
			on:click={deleteLocalStorage}
			on:keydown={deleteLocalStorage}>reset</button
		>
	</li>
</ul>

<style>
	.menuListItem {
		margin: 0;
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		padding: 0.5rem 0;
	}

	.theme-toggle {
		--duration: 500ms;
		background: none;
		border: none;
		padding: 0;
		margin: 0;
		width: 1.5rem;
		height: 1.5rem;
		color: var(--text-color);
		transition: all var(--duration) ease-in-out;
		transition: color var(--duration-hover) ease-in-out;
	}

	.sun_to_moon path {
		transition-timing-function: cubic-bezier(0, 0, 0.15, 1.25);
		transform-origin: center;
		transition-duration: calc(var(--duration) * 0.8);
	}
	.sun_to_moon g path {
		transition-property: opacity, transform;
		transition-delay: calc(var(--duration) * 0.2);
	}
	.sun_to_moon :first-child path {
		transition-property: transform, d;
	}
	[data-theme='light'] .sun_to_moon g path {
		transform: scale(0.5) rotate(45deg);
		opacity: 0;
		transition-delay: 0s;
	}
	[data-theme='light'] .sun_to_moon :first-child path {
		transform: translate3d(-12px, 10px, 0);
	}

	ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	li {
		margin: 0;
	}
</style>
