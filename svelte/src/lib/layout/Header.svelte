<script>
	import { browser } from '$app/environment';
	import ThemeToggle from '$lib/components/ThemeToggle.svelte';
	import { Button, Menu, MenuItem, MenuSeparator, Icon } from '@perfectthings/ui';
	let menu1, menu2;
	function closeSomething(e) {
		e.preventDefault(); // prevents menu auto-closing
		menu1.close(); // manually close the menu
	}
	function onMenuClick(e) {
		const { target, button } = e.detail;
		console.log(target.dataset, button.dataset);
	}
	function action1(e) {
		e.preventDefault();
		console.log('action1');
	}
</script>

<svelte:head>
	<title>Skanban</title>
	<meta name="description" content="Svelte demo app" />
</svelte:head>

<header>
	<div class="header_left">
		<Menu bind:this={menu1}>
			<MenuItem data-value="add-something"><Icon name="plus" /> Add some</MenuItem>
			<MenuItem>Add some more</MenuItem>
			<MenuSeparator />
			<MenuItem on:click={closeSomething}><Icon name="close" /> Close something</MenuItem>
		</Menu>
		<Button on:click={() => menu1.open()}>Open menu</Button>

		<div class="div1">Tab</div>
		<Menu type="context" targetSelector=".div1" bind:this={menu2}>
			<MenuItem shortcut="cmd+n" on:click={action1}>New window</MenuItem>
			<MenuItem shortcut="cmd+shift+n" on:click={action1}>New private window</MenuItem>
			<MenuSeparator />
			<MenuItem shortcut="cmd+shift+q" on:click={action1}>Close All Windows</MenuItem>
		</Menu>
	</div>
	<div class="header_center">
		<span class="pagename"> Skanban </span>
	</div>
	<div class="header_right">
		<ThemeToggle />
	</div>
</header>

<style>
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.pagename {
		font-size: 1.75rem;
	}

	.header_right {
	}
</style>
