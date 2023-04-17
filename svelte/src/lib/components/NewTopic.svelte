<script>
	import { onMount } from 'svelte';
	import { topicStore } from '$lib/store.js';

	import Plus from '$lib/icons/Plus.svelte';
	import Close from '$lib/icons/Close.svelte';
	import { clickOutside } from '$lib/utils.js';
	export let columnId;
	export let columns;
	const API = 'https://dev.rasal.de/skanban/api.php?';

	let formDialog, authorName, columnName;
	let missingInput = '';

	onMount(() => {
		// window.localStorage.setItem('SkanbanName', 'Kolja');
		authorName = window.localStorage.getItem('SkanbanName') || '';
	});

	function openNewTopicForm() {
		formDialog.showModal();
		columnName = columns[columnId].column_name;
	}

	function closeNewTopicForm(event) {
		event.preventDefault();
		formDialog.close();
	}

	function createNewTopic(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		const data = Object.fromEntries(formData);
		const date = new Date().toLocaleDateString('de-de', {
			year: 'numeric',
			month: 'numeric',
			day: 'numeric',
			hour: 'numeric',
			minute: 'numeric'
		});
		data.created = date;
		window.localStorage.setItem('SkanbanName', data.author);

		fetch(API + 'addTopic', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		})
			.then((res) => res.json())
			.then((res) => {
				// console.log(res);
				if (res.message === 'success') {
					delete data.column;
					data.id = res.topic_id;
					$topicStore[columnId].push(data);
					$topicStore = $topicStore;
					// console.log($topicStore[columnId]);
					closeNewTopicForm(e);
				} else {
					missingInput = res.message;
				}
			})
			.catch((err) => console.log(err));
	}

	// $: console.log('$topicStore', $topicStore);
</script>

<button
	class="openNewTopicButton styleLessButton"
	title="add new Topic"
	on:click={openNewTopicForm}
>
	<Plus />
</button>

<dialog class="newTopicFormDialog" bind:this={formDialog}>
	<div class="innerDialog" use:clickOutside on:click_outside={closeNewTopicForm}>
		<form class="newTopic_form" on:submit={createNewTopic}>
			<header class="newTopic_header">
				<h2>
					New Topic in
					<select name="column">
						{#each columns as column}
							<option value={column.id} selected={column.id === columnId}>
								{column.column_name}
							</option>
						{/each}
					</select>
				</h2>
				<button
					class="closeNewTopicButton styleLessButton"
					title="close Form"
					on:click={closeNewTopicForm}
				>
					<Close />
				</button>
			</header>

			<input type="text" name="title" placeholder="Topic title" value="title" />
			<textarea name="content" placeholder="Topic content">text</textarea>
			<input type="text" name="author" placeholder="Name" value={authorName} />
			<footer class="newTopic_footer">
				<input type="submit" value="submit" />
				<span class="newTopicMissingInput">{missingInput}</span>
			</footer>
		</form>
	</div>
</dialog>

<style>
	.openNewTopicButton {
		position: absolute;
		top: 0.5rem;
		right: 0.5rem;
	}
	.openNewTopicButton :global(svg g) {
		stroke: var(--malibu);
		transition: all 0.2s;
	}
	.openNewTopicButton:hover :global(svg g) {
		filter: brightness(1.2);
	}

	.newTopic_header {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		padding: 0.5rem;
	}
	.newTopic_header h2 {
		color: var(--color-text);
		text-align: center;
		font-size: 1.2rem;
		margin: 0;
	}

	.newTopic_form {
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
		padding: 0.5rem;
	}
	.newTopicFormDialog {
		max-width: 30rem;
	}

	.closeNewTopicButton {
		/* position: absolute; */
		top: 0.5rem;
		right: 0.5rem;
	}
	.closeNewTopicButton :global(svg path) {
		stroke: var(--malibu);
		transition: all 0.2s;
	}
	.closeNewTopicButton:hover :global(svg path) {
		filter: brightness(1.2);
	}

	.newTopicMissingInput {
		padding-left: 1rem;
		color: var(--error);
	}

	textarea,
	select,
	input {
		padding: 0.5rem;
		padding-bottom: 0.25rem;
		border: 1px solid var(--v-lightDark);
		border-radius: 0.25rem;
		background-color: var(--bg-color-secondary);
		color: var(--color-text);
		font-family: 'Overpass', sans-serif;
		font-size: 1rem;
	}
</style>
