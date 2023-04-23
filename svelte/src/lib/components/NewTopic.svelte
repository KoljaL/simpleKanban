<script>
	import { PUBLIC_API_URL } from '$env/static/public';
	import { getDatetimeNow } from '$lib/utils.js';
	import { onMount } from 'svelte';
	import { topicStore } from '$lib/store.js';
	import ColorPicker from '$lib/components/ColorPicker.svelte';
	import Modal from '$lib/components/Modal.svelte';
	import Plus from '$lib/icons/Plus.svelte';
	export let columnId;
	export let columns;
	let open = false;
	let authorName, columnName;
	let missingInput = '';
	onMount(() => {
		authorName = window.localStorage.getItem('SkanbanName') || '';
	});

	function openNewTopicForm() {
		open = true;
		columnName = columns[columnId].column_name;
	}

	function createNewTopic(e) {
		e.preventDefault();
		// console.log(e.target);
		const formData = new FormData(e.target);
		let data = Object.fromEntries(formData);
		data.created = getDatetimeNow();
		window.localStorage.setItem('SkanbanName', data.author);

		fetch(PUBLIC_API_URL + 'addTopic', {
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
					// delete data.column;
					data.id = res.topic_id;
					// add data to column in store
					var col = $topicStore.find((col) => col.id === columnId);
					col.topics.push(data);
					$topicStore = $topicStore;
					// console.log($topicStore[columnId]);
					open = false;
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

<Modal bind:open>
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
		</header>

		<div class="flex">
			<input type="text" name="title" placeholder="Topic title" value="title" />
			<ColorPicker />
		</div>

		<textarea name="content" placeholder="Topic content">text</textarea>
		<input type="text" name="author" placeholder="Name" value={authorName} />
		<footer class="newTopic_footer">
			<input type="submit" value="submit" />
			<span class="newTopicMissingInput">{missingInput}</span>
		</footer>
	</form>
</Modal>

<style>
	.flex {
		display: flex;
		flex-direction: row;
		gap: 0.5rem;
	}

	/* .openNewTopicButton :global(svg g) {
		stroke: var(--malibu);
		transition: all 0.2s;
		filter: brightness(0.6);
	}
	.openNewTopicButton:hover :global(svg g) {
		filter: brightness(1);
	} */

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

	.newTopicMissingInput {
		padding-left: 1rem;
		color: var(--error);
	}

	textarea {
		height: 5rem;
	}
</style>
