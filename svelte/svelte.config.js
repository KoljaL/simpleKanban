// import adapter from '@sveltejs/adapter-auto';
import adapter from '@sveltejs/adapter-static';
/** @type {import('@sveltejs/kit').Config} */
const config = {
	kit: {
		adapter: adapter({
			pages: 'docs',
			assets: 'build',
			fallback: '200.html',
			precompress: false,
			strict: true
		})
	}
};

export default config;
