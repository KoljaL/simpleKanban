export const prerender = true;
const API = 'https://dev.rasal.de/skanban/api.php?';

export const load = async ({ fetch }) => {
	const response = await fetch(API + 'start');
	const data = await response.json();
	return { data };
};
