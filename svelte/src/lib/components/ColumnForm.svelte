<script>
	import ColorPicker from './ColorPicker.svelte';
	import { topicStore, modalMessage } from '$lib/store.js';
	import Modal from '$lib/components/Modal.svelte';

	export let openModal = false;
	export let columnData;
	export let callback;
	// console.log('columnData', columnData);
	// console.log('callback', callback);

	// if (columnData && columnData.color === '') {
	// 	columnData.color = 'hsl(219, 14%, 71%)';
	// }

	// handle missing input in form
	let missingInput = '';
	$: if ($modalMessage) {
		missingInput = $modalMessage;
	}
	// $: console.log(openModal);
</script>

{#if openModal}
	<Modal bind:openModal position={columnData.position}>
		<form class="newTopic_form" on:submit={callback}>
			<header class="newTopic_header">
				<h2>New Column</h2>
			</header>

			<div class="row">
				<label>
					Name
					<input
						type="text"
						name="column_name"
						placeholder="Column name"
						value={columnData.column_name}
					/>
				</label>
				<ColorPicker colorValue={columnData.color} />
			</div>
			<footer class="newTopic_footer">
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
		border-bottom: 1px solid var(--border-color);
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
</style>
