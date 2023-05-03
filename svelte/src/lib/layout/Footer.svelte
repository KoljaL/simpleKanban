<script>
	import Github from '$lib/icons/Github.svelte';
	import SvelteMate from '$lib/components/SvelteMate.svelte';
	import { PUBLIC_REPO_URL } from '$env/static/public';
</script>

<footer>
	<div class="footer_left">
		<a href="https://dev.rasal.de/skanban/api.php?init&dbKey=dummydata" target="_blank">reset</a>
		<a href="https://dev.rasal.de/skanban/api.php?dummy=10&dbKey=dummydata" target="_blank">dummy</a
		>
	</div>
	<div class="ghLink">
		<a href={PUBLIC_REPO_URL} target="_blank" title="Link to GitHub Repo" rel="noopener noreferrer">
			<Github />
		</a>
	</div>
	<div class="footer_right">
		<SvelteMate />
	</div>
</footer>

<style>
	footer {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.footer_left a {
		opacity: 0.1;
	}

	.footer_left:hover a {
		opacity: 1;
	}

	.ghLink :global(svg) {
		margin-top: 0.35rem;
		width: 1.2rem;
		height: 1.2rem;
		fill: var(--text-color-secondary);
		transition: fill var(--duration-hover) ease-in-out;
	}

	.ghLink:hover {
		filter: none;
	}
	.ghLink:hover :global(svg) {
		filter: none;
		fill: var(--color-svelte);
	}
</style>
