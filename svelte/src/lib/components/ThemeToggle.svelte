<script>
	function toggleTheme() {
		var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
		var targetTheme = 'light';
		// faviconEl.setAttribute('href', 'img/favicon-light.png');
		if (currentTheme === 'light') {
			targetTheme = 'dark';
			// faviconEl.setAttribute('href', 'img/favicon-dark.png');
		}
		document.documentElement.setAttribute('data-theme', targetTheme);
		localStorage.setItem('Skanban-theme', targetTheme);
	}
</script>

<button
	class="theme-toggle"
	type="button"
	title="Toggle theme"
	aria-label="Toggle theme"
	on:click={toggleTheme}
	on:keydown={toggleTheme}
>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		aria-hidden="true"
		width="2rem"
		height="2rem"
		fill="currentColor"
		stroke-linecap="round"
		class="sun_to_moon"
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
</button>

<style>
	.theme-toggle {
		--duration: 500ms;
		cursor: pointer;
		background: none;
		border: none;
		padding: 0;
		margin: 0;
		margin-top: 0.25rem;
		width: 2rem;
		height: 2rem;
		color: var(--text-color-secondary);
		transition: all var(--duration) ease-in-out;
		transition: color var(--duration-hover) ease-in-out;
	}
	.theme-toggle:hover {
		color: var(--color-svelte);
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
	/* [data-theme='light'] .sun_to_moon :first-child path {
		d: path('M-12 5h30a1 1 0 0 0 9 13v24h-39Z');
		transition-delay: calc(var(--duration) * 0.2);
	}
	@supports not (d: path('')) { */
	[data-theme='light'] .sun_to_moon :first-child path {
		transform: translate3d(-12px, 10px, 0);
	}
	/* } */
</style>
