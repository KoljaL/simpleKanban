/** Dispatch event on click outside of node */
export function clickOutside(node) {
	const handleClick = (event) => {
		if (node && !node.contains(event.target) && !event.defaultPrevented) {
			// console.log('click outside', event.target);
			node.dispatchEvent(new CustomEvent('click_outside', node));
		}
	};

	document.addEventListener('click', handleClick, true);

	return {
		destroy() {
			document.removeEventListener('click', handleClick, true);
		}
	};
}

export function getDatetimeNow() {
	const date = new Date();
	const year = date.getFullYear();
	const month = date.getMonth() + 1;
	const day = date.getDate();
	const hours = ('0' + date.getHours()).substr(-2);
	const minutes = ('0' + date.getMinutes()).substr(-2);
	const seconds = ('0' + date.getSeconds()).substr(-2);
	// console.log('getDate', `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`);
	return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

export function formatDatetime(datetime) {
	const date = new Date(datetime);
	const year = date.getFullYear();
	const month = date.getMonth() + 1;
	const day = date.getDate();
	const hours = date.getHours();
	const minutes = date.getMinutes();
	const seconds = date.getSeconds();
	// console.log('formatDate', `${day}.${month}.${year} ${hours}:${minutes}`);
	return `${day}.${month}.${year} ${hours}:${minutes}`;
}
