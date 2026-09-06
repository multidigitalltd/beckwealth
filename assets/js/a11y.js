/**
 * Beck Wealth – סרגל נגישות (נטען עצל בלחיצה ראשונה על הכפתור).
 * העדפות נשמרות ב-localStorage ומוחלות מוקדם על-ידי main.js.
 *
 * @package BeckWealth
 */
( function () {
	'use strict';

	const api = window.beckwealthA11y;
	const panel = document.getElementById( 'a11y-panel' );
	const toggle = document.getElementById( 'a11y-toggle' );
	if ( ! api || ! panel || ! toggle ) {
		return;
	}

	const html = document.documentElement;
	const closeBtn = panel.querySelector( '.a11y-panel__close' );
	const buttons = panel.querySelectorAll( '[data-a11y]' );
	const guide = document.getElementById( 'a11y-guide' );
	let lastFocus = null;

	const focusable = () => Array.from( panel.querySelectorAll( 'button, a[href], [tabindex]:not([tabindex="-1"])' ) ).filter( ( el ) => ! el.hidden );

	const render = () => {
		const prefs = api.get();
		buttons.forEach( ( btn ) => {
			const key = btn.dataset.a11y;
			if ( key === 'font-up' || key === 'font-down' || key === 'reset' ) {
				return;
			}
			btn.setAttribute( 'aria-pressed', prefs[ key ] ? 'true' : 'false' );
		} );
		const size = panel.querySelector( '[data-a11y-size]' );
		if ( size ) {
			const level = parseInt( prefs.font, 10 ) || 0;
			size.textContent = ( level > 0 ? '+' : '' ) + level;
		}
		if ( guide ) {
			guide.hidden = ! prefs.guide;
		}
	};

	const open = () => {
		lastFocus = document.activeElement;
		panel.hidden = false;
		requestAnimationFrame( () => panel.setAttribute( 'data-open', 'true' ) );
		html.classList.add( 'a11y-panel-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		render();
		( closeBtn || focusable()[ 0 ] ).focus();
	};

	const close = () => {
		panel.removeAttribute( 'data-open' );
		html.classList.remove( 'a11y-panel-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		window.setTimeout( () => {
			panel.hidden = true;
		}, 300 );
		( lastFocus && lastFocus.focus ? lastFocus : toggle ).focus();
	};

	// לכידת פוקוס בתוך הפאנל + ESC.
	panel.addEventListener( 'keydown', ( e ) => {
		if ( e.key === 'Escape' ) {
			e.preventDefault();
			close();
			return;
		}
		if ( e.key !== 'Tab' ) {
			return;
		}
		const items = focusable();
		if ( ! items.length ) {
			return;
		}
		const first = items[ 0 ];
		const last = items[ items.length - 1 ];
		if ( e.shiftKey && document.activeElement === first ) {
			e.preventDefault();
			last.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			e.preventDefault();
			first.focus();
		}
	} );

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', close );
	}

	// סגירה בלחיצה מחוץ לפאנל.
	document.addEventListener( 'click', ( e ) => {
		if ( ! panel.hidden && ! panel.contains( e.target ) && e.target !== toggle && ! toggle.contains( e.target ) ) {
			close();
		}
	} );

	buttons.forEach( ( btn ) => {
		btn.addEventListener( 'click', () => {
			const key = btn.dataset.a11y;
			const prefs = Object.assign( {}, api.get() );

			if ( key === 'reset' ) {
				api.set( {} );
				render();
				return;
			}
			if ( key === 'font-up' || key === 'font-down' ) {
				let level = parseInt( prefs.font, 10 ) || 0;
				level = key === 'font-up' ? Math.min( 3, level + 1 ) : Math.max( -1, level - 1 );
				prefs.font = level;
				api.set( prefs );
				render();
				return;
			}
			if ( key === 'guide' ) {
				prefs.guide = ! prefs.guide;
				api.set( prefs );
				render();
				return;
			}
			// ניגודיות והיפוך צבעים – זה או זה.
			if ( key === 'contrast' && ! prefs.contrast ) {
				prefs.invert = false;
			}
			if ( key === 'invert' && ! prefs.invert ) {
				prefs.contrast = false;
			}
			prefs[ key ] = ! prefs[ key ];
			api.set( prefs );
			render();
		} );
	} );

	// סרגל קריאה עוקב אחרי העכבר.
	if ( guide ) {
		document.addEventListener( 'mousemove', ( e ) => {
			if ( ! guide.hidden ) {
				guide.style.top = e.clientY + 'px';
			}
		}, { passive: true } );
	}

	window.beckwealthA11yPanel = { open, close };
	render();
}() );
