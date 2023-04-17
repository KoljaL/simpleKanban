<script>
	import Plus from '$lib/icons/Plus.svelte';
	export let columnId;
	export let columns;
	let formDialog;
	function addNewTopicForm() {
		formDialog.showModal();
		console.log('add new topic', columnId);
		console.log('add new topic', columns);
	}
</script>

<button class="newTopic" on:click={addNewTopicForm}>
	<Plus />
</button>
<dialog bind:this={formDialog}>
	<header class="new-topic__header">
		<h2>New Topic</h2>
		<span class="new-topic__header__close header_icon icon_close" onclick="closeNewTopic(event)" />
	</header>
	<form class="new-topic__form">
		<select name="column">
			{#each columns as column}
				<option value={column.id} selected={column.id === columnId}>
					{column.column_name}
				</option>
			{/each}
		</select>
		<input type="text" name="title" placeholder="Topic title" />
		<textarea name="content" placeholder="Topic content" />
		<!-- <input type="text" name="author" placeholder="Name" value="${authorName}" /> -->
		<footer class="new-topic__footer">
			<span
				class="new-topic__footer__add-topic"
				role="button"
				onclick="addTopic(event, ${columnId})">Add Topic</span
			>
			<span class="new-topic__footer__error" />
		</footer>
	</form>
</dialog>

<style>
	.newTopic {
		position: absolute;
		top: 0.5rem;
		right: 0.5rem;
		background: none;
		border: none;
		cursor: pointer;
		padding: 0;
	}
	.newTopic :global(svg g) {
		stroke: var(--malibu);
		transition: all 0.2s;
	}
	.newTopic:hover :global(svg g) {
		filter: brightness(1.2);
	}
</style>
