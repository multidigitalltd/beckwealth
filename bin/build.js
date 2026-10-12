/**
 * Beck Wealth – בניית קבצי Minified (CSS + JS) באמצעות esbuild.
 * שימוש: npm run build   (קבצי ה-.min נכללים ב-git כדי שלא יידרש build בשרת)
 */
'use strict';

const path = require( 'path' );
const fs = require( 'fs' );
const esbuild = require( 'esbuild' );

const root = path.resolve( __dirname, '..' );
const watch = process.argv.includes( '--watch' );

const entries = [
	...fs.readdirSync( path.join( root, 'assets/css' ) ).filter( ( f ) => f.endsWith( '.css' ) && ! f.endsWith( '.min.css' ) ).map( ( f ) => 'assets/css/' + f ),
	...fs.readdirSync( path.join( root, 'assets/js' ) ).filter( ( f ) => f.endsWith( '.js' ) && ! f.endsWith( '.min.js' ) ).map( ( f ) => 'assets/js/' + f ),
];

async function build() {
	for ( const entry of entries ) {
		const out = entry.replace( /\.(css|js)$/, '.min.$1' );
		await esbuild.build( {
			entryPoints: [ path.join( root, entry ) ],
			outfile: path.join( root, out ),
			minify: true,
			bundle: false,
			charset: 'utf8',
			legalComments: 'none',
			target: [ 'es2019', 'chrome90', 'firefox90', 'safari14' ],
			logLevel: 'error',
		} );
		const a = fs.statSync( path.join( root, entry ) ).size;
		const b = fs.statSync( path.join( root, out ) ).size;
		console.log( `${ out }  ${ ( a / 1024 ).toFixed( 1 ) }KB → ${ ( b / 1024 ).toFixed( 1 ) }KB` );
	}
}

build().then( () => {
	if ( watch ) {
		console.log( 'watching…' );
		fs.watch( path.join( root, 'assets' ), { recursive: true }, ( _e, file ) => {
			if ( file && ! /\.min\./.test( file ) ) {
				build().catch( console.error );
			}
		} );
	}
} ).catch( ( e ) => {
	console.error( e );
	process.exit( 1 );
} );
