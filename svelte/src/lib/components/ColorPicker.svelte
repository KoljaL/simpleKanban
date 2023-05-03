<script>
	let colorSet = {
		white0: 'hsl(219, 14%, 91%)',
		white1: 'hsl(219, 14%, 71%)',
		white2: 'hsl(219, 14%, 61%)',
		black0: 'hsl(220, 13%, 28%)',
		black1: 'hsl(220, 13%, 18%)',
		black2: 'hsl(220, 13%, 8%)',
		red0: 'hsl(15, 100%, 60%)',
		red1: 'hsl(15, 100%, 50%)',
		red2: 'hsl(15, 100%, 40%)',
		yellow0: 'hsl(60, 100%, 60%)',
		yellow1: 'hsl(60, 100%, 35%)',
		yellow2: 'hsl(60, 100%, 25%)',
		green0: 'hsl(120, 100%, 42%)',
		green1: 'hsl(120, 100%, 32%)',
		green2: 'hsl(120, 100%, 22%)',
		blue0: 'hsl(211, 100%, 57%)',
		blue1: 'hsl(211, 100%, 47%)',
		blue2: 'hsl(211, 100%, 37%)',
		purple0: 'hsl(270, 32%, 55%)',
		purple1: 'hsl(270, 32%, 45%)',
		purple2: 'hsl(270, 32%, 35%)',
		pink0: 'hsl(320, 56%, 57%)',
		pink1: 'hsl(320, 56%, 47%)',
		pink2: 'hsl(320, 56%, 37%)'
	};

	export let colorValue = '#abb2bf';
	// console.log(colorValue);
	let columns = 3;
	let showColorPicker = false;

	function openColorPicker(event) {
		event.preventDefault();
		showColorPicker = !showColorPicker;
	}

	function setColor(event) {
		event.preventDefault();
		colorValue = HSLToHex(event.target.dataset.colorvalue);
		showColorPicker = false;
	}

	function HSLToHex(h, s = null, l = null) {
		if (s === null && l === null) {
			console.log(h);
			h.split(',').map((item, index) => {
				if (index === 0) {
					h = item.replace('hsl(', '');
				} else if (index === 1) {
					s = item.replace('%', '');
				} else if (index === 2) {
					l = item.replace('%)', '');
				}
			});
		}

		s /= 100;
		l /= 100;

		let c = (1 - Math.abs(2 * l - 1)) * s,
			x = c * (1 - Math.abs(((h / 60) % 2) - 1)),
			m = l - c / 2,
			r = 0,
			g = 0,
			b = 0;

		if (0 <= h && h < 60) {
			r = c;
			g = x;
			b = 0;
		} else if (60 <= h && h < 120) {
			r = x;
			g = c;
			b = 0;
		} else if (120 <= h && h < 180) {
			r = 0;
			g = c;
			b = x;
		} else if (180 <= h && h < 240) {
			r = 0;
			g = x;
			b = c;
		} else if (240 <= h && h < 300) {
			r = x;
			g = 0;
			b = c;
		} else if (300 <= h && h < 360) {
			r = c;
			g = 0;
			b = x;
		}
		// Having obtained RGB, convert channels to hex
		r = Math.round((r + m) * 255).toString(16);
		g = Math.round((g + m) * 255).toString(16);
		b = Math.round((b + m) * 255).toString(16);

		// Prepend 0s, if necessary
		if (r.length == 1) r = '0' + r;
		if (g.length == 1) g = '0' + g;
		if (b.length == 1) b = '0' + b;
		console.log('#' + r + g + b);
		return '#' + r + g + b;
	}
</script>

<div class="wrapper">
	<label
		>Color
		<input
			type="color"
			name="color"
			class="colorValueInput"
			on:click={openColorPicker}
			on:keydown={openColorPicker}
			bind:value={colorValue}
		/>
	</label>

	{#if showColorPicker}
		<div class="colors" style="--grid-template-columns: repeat({columns},1.5rem)">
			{#each Object.keys(colorSet) as color}
				<button
					name="colorValue"
					title={color[0].toUpperCase() + color.slice(1)}
					data-colorvalue={colorSet[color]}
					style="background-color: {colorSet[color]}"
					on:click={setColor}
					on:keydown={setColor}
				/>
			{/each}
		</div>
	{/if}
</div>

<style>
	.wrapper {
		position: relative;
	}

	.colorValueInput {
		height: 1.8rem;
		padding: 0.25rem;
		width: 1.8rem;
	}

	.colorValueInput::-webkit-color-swatch {
		border-radius: var(--border-radius-s);
		border: none;
	}
	.colorValueInput::-moz-color-swatch {
		border-radius: var(--border-radius-s);
		border: none;
	}
	.colors {
		position: absolute;
		top: -2rem;
		left: 0;
		display: grid;
		grid-template-columns: var(--grid-template-columns);
		gap: 0.25rem;
		border: 1px solid var(--border-color);
		border-radius: var(--border-radius-m);
		padding: 0.5rem;
		background-color: var(--bg-color-primary);
	}

	button {
		background-color: transparent;
		border: 1px solid var(--border-color);
		border-radius: var(--border-radius-m);
		padding: 0.25rem 0.5rem;
		width: 1.5rem;
		height: 1.5rem;
		cursor: pointer;
	}
</style>
