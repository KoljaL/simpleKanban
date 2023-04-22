<script>
	// window.addEventListener('DOMContentLoaded', () => {
	// 	const b1 = new OnOffButton('#btn1');
	// 	const b2 = new OnOffButton('#btn2');
	// 	console.log(b1, b2);
	// });

	// class OnOffButton {
	// 	constructor(el) {
	// 		this.el = document.querySelector(el);
	// 		this.el?.addEventListener('click', this.power.bind(this));
	// 	}
	// 	power() {
	// 		const pressed = this.el?.getAttribute('aria-pressed') === 'true';
	// 		this.el?.setAttribute('aria-pressed', `${!pressed}`);
	// 	}
	// }
	let toggleButton;
	let toggleButtonWrapper;
	function toggleTheme() {
		const pressed = toggleButton.getAttribute('aria-pressed') === 'true';
		toggleButton.setAttribute('aria-pressed', `${!pressed}`);
		toggleButtonWrapper.classList.toggle('col--dark');
	}
</script>

<main>
	<!--
	<div class="col">
	 <button id="btn1" class="btn" type="button" aria-pressed="false">
			<svg class="btn__icon" viewBox="0 0 24 24" width="24px" height="24px" aria-hidden="true">
				<g stroke="currentColor" stroke-width="2" stroke-linecap="round">
					<polyline points="12,1 12,10" />
					<circle
						fill="none"
						cx="12"
						cy="13"
						r="9"
						stroke-dasharray="49.48 7.07"
						stroke-dashoffset="10.6"
					/>
				</g>
			</svg>
			<span class="btn__sr">Power (Light)</span>
		</button>
	</div> -->
	<div class="col col--dark" bind:this={toggleButtonWrapper}>
		<button
			id="btn2"
			class="btn"
			type="button"
			aria-pressed="false"
			bind:this={toggleButton}
			on:click={toggleTheme}
			on:keydown={toggleTheme}
		>
			<svg class="btn__icon" viewBox="0 0 24 24" width="24px" height="24px" aria-hidden="true">
				<g stroke="currentColor" stroke-width="2" stroke-linecap="round">
					<polyline points="12,1 12,10" />
					<circle
						fill="none"
						cx="12"
						cy="13"
						r="9"
						stroke-dasharray="49.48 7.07"
						stroke-dashoffset="10.6"
					/>
				</g>
			</svg>
			<span class="btn__sr">Power (Dark)</span>
		</button>
	</div>
</main>

<style>
	* {
		border: 0;
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}
	:root {
		/* surface and button */
		--hue: 223;
		--sat: 10%;
		/* button light color */
		--hue2: 223;
		--sat2: 90%;
		--light2: 60%;
		/* other */
		--primary: hsl(var(--hue2), var(--sat2), var(--light2));
		--trans-dur: 0.3s;
		--trans-timing: ease-in-out;
		--trans-timing2: cubic-bezier(0.42, -1.84, 0.42, 1.84);
		--trans-timing3: cubic-bezier(0.42, 0, 0.42, 1.84);
		font-size: 20px;
	}

	button {
		font: 1em/1.5 sans-serif;
	}
	/* body {
		height: 100vh;
		transition: background-color var(--trans-dur), color var(--trans-dur);
	} */
	main {
		display: flex;
		flex-direction: column;
		width: 100%;
		height: 100%;
		transform: scale(0.2);
	}

	/* Light */
	.col {
		/* background-image: linear-gradient(
			hsl(var(--hue), var(--sat), 90%),
			hsl(var(--hue), var(--sat), 75%)
		); */
		display: flex;
		flex: 1;
		padding: 1.5em 0;
		min-height: 13em;
		transition: all 1.5s ease;
	}
	.btn {
		background-color: transparent;
		background-image: linear-gradient(
			hsl(var(--hue), var(--sat), 80%),
			hsl(var(--hue), var(--sat), 85%)
		);
		border-radius: 50%;
		box-shadow: 0 0 0 0.125em hsla(var(--hue2), var(--sat2), 50%, 0),
			0 0 0.25em hsl(var(--hue), var(--sat), 55%) inset,
			0 0.125em 0.125em hsl(var(--hue), var(--sat), 90%);
		cursor: pointer;
		margin: auto;
		outline: transparent;
		position: relative;
		width: 10em;
		height: 10em;
		transition: box-shadow var(--trans-dur) var(--trans-timing);
		-webkit-tap-highlight-color: transparent;
	}
	.btn:focus-visible {
		box-shadow: 0 0 0 0.125em hsla(var(--hue2), var(--sat2), 50%, 1),
			0 0 0.25em hsl(var(--hue), var(--sat), 55%) inset,
			0 0.125em 0.125em hsl(var(--hue), var(--sat), 90%);
	}
	.btn:before,
	.btn:after {
		border-radius: inherit;
		content: '';
		display: block;
		position: absolute;
	}
	.btn:before {
		background-image: linear-gradient(
			hsl(var(--hue), var(--sat), 90%),
			hsl(var(--hue), var(--sat), 80%)
		);
		box-shadow: 0 0.75em 0.75em 0.25em hsla(var(--hue), 0%, 0%, 0.3);
		top: 1.5em;
		left: 1.5em;
		width: 7em;
		height: 7em;
		transition: box-shadow var(--trans-dur) var(--trans-timing2),
			transform var(--trans-dur) var(--trans-timing2);
	}
	.btn:after {
		background-color: hsl(var(--hue), var(--sat), 75%);
		background-image: linear-gradient(
			hsla(var(--hue), var(--sat), 90%, 0),
			hsl(var(--hue), var(--sat), 90%)
		);
		box-shadow: 0 0.0625em 0 hsl(var(--hue), var(--sat), 90%) inset,
			0 -0.0625em 0 hsl(var(--hue), var(--sat), 90%) inset,
			0 0 0 0 hsla(var(--hue2), var(--sat2), var(--light2), 0.1) inset;
		top: 3em;
		left: 3em;
		width: 4em;
		height: 4em;
		transition: background-color var(--trans-dur) var(--trans-timing),
			box-shadow var(--trans-dur) var(--trans-timing),
			transform var(--trans-dur) var(--trans-timing2);
	}
	.btn__icon {
		display: block;
		position: absolute;
		top: calc(50% - 0.75em);
		left: calc(50% - 0.75em);
		width: 1.5em;
		height: 1.5em;
		transition: filter var(--trans-dur) var(--trans-timing);
		z-index: 1;
	}
	.btn__icon g {
		stroke: hsl(var(--hue), var(--sat), 70%);
		transition: stroke var(--trans-dur) var(--trans-timing);
	}
	.btn__sr {
		overflow: hidden;
		position: absolute;
		width: 1px;
		height: 1px;
	}
	:global(.btn[aria-pressed='true']):before,
	:global(.btn[aria-pressed='true']):after,
	:global(.btn[aria-pressed='true']) .btn__icon {
		transform: scale(0.98);
	}
	:global(.btn[aria-pressed='true']):before {
		box-shadow: 0 0.375em 0.375em 0 hsla(var(--hue), 0%, 0%, 0.3);
		transition-timing-function: var(--trans-timing3);
	}
	:global(.btn[aria-pressed='true']):after {
		background-color: hsl(var(--hue), var(--sat), 90%);
		box-shadow: 0 0.0625em 0 hsla(var(--hue2), var(--sat2), var(--light2), 0.5) inset,
			0 -0.0625em 0 hsla(var(--hue2), var(--sat2), var(--light2), 0.5) inset,
			0 0 0.75em 0.25em hsla(var(--hue2), var(--sat2), var(--light2), 0.1) inset;
		transition-timing-function: var(--trans-timing), var(--trans-timing), var(--trans-timing3);
	}
	:global(.btn[aria-pressed='true']) .btn__icon {
		filter: drop-shadow(0 0 0.25em var(--primary));
	}
	:global(.btn[aria-pressed='true']) .btn__icon g {
		stroke: var(--primary);
	}

	/* Dark */
	.col--dark {
		/* background-image: linear-gradient(
			hsl(var(--hue), var(--sat), 20%),
			hsl(var(--hue), var(--sat), 5%)
		); */
	}
	.col--dark .btn {
		background-image: linear-gradient(
			hsl(var(--hue), var(--sat), 10%),
			hsl(var(--hue), var(--sat), 15%)
		);
		box-shadow: 0 0 0 0.125em hsla(var(--hue2), var(--sat2), 50%, 0),
			0 0 0.25em hsl(var(--hue), var(--sat), 5%) inset,
			0 0.125em 0.125em hsl(var(--hue), var(--sat), 25%);
	}
	.col--dark .btn:focus-visible {
		box-shadow: 0 0 0 0.125em hsla(var(--hue2), var(--sat2), 50%, 1),
			0 0 0.25em hsl(var(--hue), var(--sat), 5%) inset,
			0 0.125em 0.125em hsl(var(--hue), var(--sat), 25%);
	}
	.col--dark .btn:before {
		background-image: linear-gradient(
			hsl(var(--hue), var(--sat), 20%),
			hsl(var(--hue), var(--sat), 10%)
		);
		box-shadow: 0 0.75em 0.75em 0.25em hsla(var(--hue), 0%, 0%, 0.7);
	}
	.col--dark .btn:after {
		background-color: hsl(var(--hue), var(--sat), 10%);
		background-image: linear-gradient(
			hsla(var(--hue), var(--sat), 20%, 0),
			hsl(var(--hue), var(--sat), 20%)
		);
		box-shadow: 0 0.0625em 0 hsl(var(--hue), var(--sat), 25%) inset,
			0 -0.0625em 0 hsl(var(--hue), var(--sat), 25%) inset,
			0 0 0 0 hsla(var(--hue2), var(--sat2), var(--light2), 0.1) inset;
	}
	.col--dark .btn__icon g {
		stroke: hsl(var(--hue), var(--sat), 5%);
	}
	.col--dark :global(.btn[aria-pressed='true']):before {
		box-shadow: 0 0.375em 0.375em 0 hsla(var(--hue), 0%, 0%, 0.7);
	}
	.col--dark :global(.btn[aria-pressed='true']):after {
		background-color: hsl(var(--hue), var(--sat), 20%);
		box-shadow: 0 0.0625em 0 hsla(var(--hue2), var(--sat2), var(--light2), 0.5) inset,
			0 -0.0625em 0 hsla(var(--hue2), var(--sat2), var(--light2), 0.5) inset,
			0 0 0.75em 0.25em hsla(var(--hue2), var(--sat2), var(--light2), 0.1) inset;
	}
</style>
