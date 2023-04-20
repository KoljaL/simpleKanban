import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';

//https://github.com/sveltejs/kit/issues/4039
function VitePluginRenameCSSFontFiles() {
	return {
		name: 'Remove hash from font files',
		apply: 'build',
		enforce: 'post',
		config(config) {
			const defaultAssetFileNames = config.build.rollupOptions.output.assetFileNames;
			config.build.rollupOptions.output.assetFileNames = ({ type, name }) => {
				if (type === 'asset' && /\.(woff2?|ttf|otf)$/.test(name)) return `[name].[ext]`;
				return defaultAssetFileNames;
			};
		}
	};
}
export default defineConfig({
	plugins: [sveltekit(), VitePluginRenameCSSFontFiles()]
});
