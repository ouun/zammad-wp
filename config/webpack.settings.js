// Webpack settings exports.
module.exports = {
	entries: {
		// JS files.
		chat: './assets/js/shared/chat.js',
		form: './assets/js/shared/form.js',

		// CSS files.
		'chat-style': './assets/css/shared/chat.scss',
		'form-style': './assets/css/shared/form.scss',
		style: './assets/css/frontend/style.css',
	},
	filename: {
		js: 'js/[name].js',
		css: 'css/[name].css',
	},
	paths: {
		src: {
			base: './assets/',
			css: './assets/css/',
			js: './assets/js/',
		},
		dist: {
			base: './dist/',
			clean: ['./images', './css', './js'],
		},
	},
	stats: {
		// Copied from `'minimal'`.
		all: false,
		errors: true,
		maxModules: 0,
		modules: true,
		warnings: true,
		// Our additional options.
		assets: true,
		errorDetails: true,
		excludeAssets: /\.(jpe?g|png|gif|svg|woff|woff2)$/i,
		moduleTrace: true,
		performance: true,
	},
	copyWebpackConfig: {
		from: '**/*.{jpg,jpeg,png,gif,svg,eot,ttf,woff,woff2}',
		to: '[path][name].[ext]',
	},
	ImageminPlugin: {
		test: /\.(jpe?g|png|gif|svg)$/i,
	},
	BrowserSyncConfig: {
		host: 'localhost',
		port: 1983,
		proxy: 'http://on.test',
		open: false,
		files: [
			'**/*.php',
			'dist/js/**/*.js',
			'dist/css/**/*.css',
			'dist/svg/**/*.svg',
			'dist/images/**/*.{jpg,jpeg,png,gif}',
			'dist/fonts/**/*.{eot,ttf,woff,woff2,svg}',
		],
	},
	performance: {
		maxAssetSize: 100000,
	},
	manifestConfig: {
		basePath: '',
	},
};
