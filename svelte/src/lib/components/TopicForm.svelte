<script>
	import ColorPicker from './ColorPicker.svelte';
	import { topicStore, modalMessage } from '$lib/store.js';
	import Modal from '$lib/components/Modal.svelte';

	export let openModal = false;
	export let topicData;
	export let callback;

	// if (topicData && topicData.color === '') {
	// 	topicData.color = 'hsl(219, 14%, 71%)';
	// }

	// handle missing input in form
	let missingInput = '';
	$: if ($modalMessage) {
		missingInput = $modalMessage;
	}
</script>

{#if openModal}
	<Modal bind:openModal position={topicData.position}>
		<form class="newTopic_form" on:submit={callback}>
			<header class="newTopic_header">
				<h2>
					New Topic in
					<select name="column" class="column">
						{#each $topicStore as column}
							<option value={column.id} selected={column.id === topicData.column}>
								{column.column_name}
							</option>
						{/each}
					</select>
				</h2>
			</header>

			<div class="row">
				<label>
					Title
					<input type="text" name="title" placeholder="Topic title" value={topicData.title} />
				</label>
				<ColorPicker colorValue={topicData.color} />
			</div>

			<label>
				Text
				<textarea name="content" placeholder="Topic content">{topicData.content}</textarea>
			</label>
			<label>
				Name
				<input type="text" name="author" placeholder="Name" value={topicData.author} />
			</label>
			<label>
				to do until
				<input type="date" name="deadline" value={topicData.deadline} />
			</label>
			<footer class="newTopic_footer">
				<input type="hidden" name="created" value={topicData.created} />
				<input type="submit" value="submit" />
				<span class="newTopicMissingInput">{missingInput}</span>
			</footer>
		</form>
	</Modal>
{/if}

<style>
	.row {
		display: flex;
		flex-direction: row;
		gap: 0.5rem;
	}

	.newTopic_header {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		padding-left: 0rem;
		padding-right: 2rem;
		padding-top: 0.5rem;
		padding-bottom: 0.5rem;
		border-bottom: 1px solid var(--color-border);
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
		/* padding: 0.5rem; */
	}

	.newTopicMissingInput {
		padding-left: 1rem;
		color: var(--error);
	}

	.column {
		margin-left: 0.5rem;
	}
	textarea {
		height: 5rem;
		transition: all 0.3s ease;
	}

	:global(.expanded) textarea {
		height: 50vh;
	}
</style>
