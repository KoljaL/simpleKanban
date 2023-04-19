<script>
	import Header from '$lib/layout/Header.svelte';
	import Footer from '$lib/layout/Footer.svelte';
	// import '$lib/styles/pico.css';
	import '$lib/styles/tooltip.css';
	import '$lib/styles/styles.css';
</script>

<Header />

<main>
	<slot />
</main>

<Footer />

<style>
	:root {
		--header-height: 3rem;
		--footer-height: 2rem;
		--main-height: calc(100svh - var(--header-height) - var(--footer-height));
		--max-width: 64rem;

		--bg-color-primary: #282c34;
		--bg-color-secondary: #3e4451;
		--bg-color-tertiary: #545c6f;

		--bg-color-primary: hsl(218.2, 26.8%, 8%);
		--bg-color-secondary: hsl(222, 16.1%, 12.2%);
		--bg-color-tertiary: #545c6f;
		--color-border: hsl(216, 11.1%, 17.6%);

		--color-svelte: #ff3e00;
		--color-green: #00ab74;
		--color-yellow: #ffa000;
		--color-blue: #072cc4;
		--color-text: #abb2bf;
		--color-text-secondary: #7f848e;
		--color-text-tertiary: #5c6370;

		--chalky: #e5c07b;
		--coral: #e06c75;
		--dark: #5c6370;
		--error: #f44747;
		--fountainBlue: hsl(187, 47%, 55%);
		--green: #98c379;
		--invalid: #ffffff;
		--lightDark: #7f848e;
		--lightWhite: #abb2bf;
		--malibu: #61afef;
		--purple: #c678dd;
		--whiskey: #d19a66;
		--deepRed: #be5046;

		--v-chalky: #e5c07b;
		--v-coral: #ef596f;
		--v-dark: #5c6370;
		--v-error: #f44747;
		--v-fountainBlue: hsl(184, 64%, 47%);
		--v-green: #89ca78;
		--v-invalid: #ffffff;
		--v-lightDark: #7f848e;
		--v-lightWhite: #abb2bf;
		--v-malibu: #61afef;
		--v-purple: #d55fde;
		--v-whiskey: #d19a66;
		--v-deepRed: #be5046;
	}

	@media (prefers-color-scheme: dark) {
		:root {
			--text-color: hsl(222.9, 18.9%, 92.7%);
			--text-color-secondary: hsl(208.2, 7.3%, 45.7%);
			--bg-color-primary: hsl(218.2, 26.8%, 8%);
			--bg-color-secondary: hsl(222, 16.1%, 12.2%);
			--bg-color-tertiary: #545c6f;
			--color-border: hsl(216, 11.1%, 17.6%);
		}
	}

	@media (prefers-color-scheme: light) {
		:root {
			--text-color: hsl(226.7, 18.4%, 28.8%);
			--text-color-secondary: hsl(208.2, 7.3%, 45.7%);
			--bg-color-primary: hsl(204, 45.5%, 97.8%);
			--bg-color-secondary: hsl(0, 0%, 100%);
			--bg-color-tertiary: #545c6f;
			--color-border: hsl(0, 0%, 87.8%);
		}
	}

	:global(body) {
		min-height: 100svh;
		color: var(--text-color);
		background-color: var(--bg-color-primary);
		margin: 0;
		padding: 0;
		font-family: 'Overpass', sans-serif;
		font-size: 16px;
		font-weight: 400;
		line-height: 1.5;
	}

	:global(#SKA > header) {
		width: 100%;
		max-width: var(--max-width);
		height: var(--header-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-svelte);
	}

	:global(#SKA) > main {
		width: 100%;
		max-width: var(--max-width);
		height: var(--main-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
	}

	:global(#SKA > footer) {
		width: 100%;
		max-width: var(--max-width);
		height: var(--footer-height);
		padding: 0;
		margin: 0 auto;
		background-color: var(--bg-color-primary);
		color: var(--color-text);
	}
</style>
