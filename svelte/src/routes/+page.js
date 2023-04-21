// export const prerender = true;
// export const ssr = false;
// const API = 'https://dev.rasal.de/skanban/api.php?';
import { PUBLIC_API_URL } from '$env/static/public';
import { building } from '$app/environment';
// console.log(PUBLIC_API_URL);

export const load = async ({ fetch }) => {
	if (building) return;

	const response = await fetch(PUBLIC_API_URL + 'start');
	let data = await response.json();
	return { data };
};
