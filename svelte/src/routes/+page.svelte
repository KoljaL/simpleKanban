<script>
	import Columns from '$lib/components/Columns.svelte';
	export let data;

	console.log(data);
</script>

<Columns columns={data.data.columns} />

<style>
	section {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		flex: 0.6;
	}
</style>
