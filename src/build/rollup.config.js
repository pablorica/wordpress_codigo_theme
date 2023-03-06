'use strict';

/**
 * External dependencies
 */
const path = require( 'path' );
const { babel } = require( '@rollup/plugin-babel' );
const { nodeResolve } = require( '@rollup/plugin-node-resolve' );
const commonjs = require( '@rollup/plugin-commonjs' );
const multi = require( '@rollup/plugin-multi-entry' );
const replace = require( '@rollup/plugin-replace' );
const vue = require('rollup-plugin-vue' );
/**
 * Internal dependencies
 */
const banner = require( './banner.js' );
const footer = require( './footer.js' );

// Populate Bootstrap version specific variables.
let bsVersion = 5;
let bsSrcFile = 'bootstrap.js';
let vueSrcFile = 'vueMain.js';
let fileDest = 'codigo';
let globals = {
	jquery: 'jQuery', // Ensure we use jQuery which is always available even in noConflict mode
	'@popperjs/core': 'Popper',
};

const external = [ 'jquery' ];

const plugins = [
	babel( {
        // Only transpile our source code.
        // exclude: 'node_modules/**',
		browserslistEnv: `bs${ bsVersion }`,
		// Include the helpers in the bundle, at most one copy of each.
		babelHelpers: 'bundled',
	} ),
    vue(),
	replace( {
		'process.env.NODE_ENV': '"production"',
		preventAssignment: true,
	} ),
	nodeResolve(),
	commonjs(),
	multi(),
];

module.exports = {
	input: [
		path.resolve( __dirname, `../js/${ bsSrcFile }` ),
        path.resolve( __dirname, `../js/${ vueSrcFile }` ),
		path.resolve( __dirname, '../js/skip-link-focus-fix.js' ),
		path.resolve( __dirname, '../js/custom-javascript.js' ),
	],
	output: [
		{
			banner: banner(''),
            footer: footer(),
			file: path.resolve( __dirname, `../../js/${ fileDest }.js` ),
			format: 'umd',
			globals,
			name: 'codigo',
		},
		{
			banner: banner(''),
            footer: footer(),
			file: path.resolve( __dirname, `../../js/${ fileDest }.min.js` ),
			format: 'umd',
			globals,
			name: 'codigo',
		},
	],
	external,
	plugins,
};
