/**
 * Beck Wealth – סקריפט גלובלי (Vanilla JS, ללא תלויות). נטען בכל עמוד.
 * תפריט נייד, פס התקדמות גלילה, הודעת פרטיות, אתחול העדפות נגישות
 * וטעינה עצלה של סרגל הנגישות. סקריפטים כבדים יותר נטענים רק כשנדרש.
 *
 * @package BeckWealth
 */
( function () {
	'use strict';

	const data = window.beckwealthData || {};
	const i18n = data.i18n || {};
	const html = document.documentElement;
	html.classList.remove( 'no-js' );

	const store = {
		get( key ) {
			try {
				return window.localStorage.getItem( key );
			} catch ( e ) {
				return null;
			}
		},
		set( key, value ) {
			try {
				window.localStorage.setItem( key, value );
			} catch ( e ) {
				// אחסון חסום – מתעלמים.
			}
		},
	};

	/* ---------- תפריט נייד ---------- */
	const nav = document.getElementById( 'site-navigation' );
	const toggle = document.querySelector( '.menu-toggle' );
	const menu = document.getElementById( 'primary-menu' );

	if ( nav && toggle && menu ) {
		const srText = toggle.querySelector( '.screen-reader-text' );
		const setOpen = ( open ) => {
			nav.classList.toggle( 'is-open', open );
			document.body.classList.toggle( 'menu-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( srText ) {
				srText.textContent = open ? ( i18n.closeMenu || 'Close menu' ) : ( i18n.openMenu || 'Open menu' );
			}
		};
		toggle.addEventListener( 'click', () => setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' ) );
		document.addEventListener( 'keydown', ( e ) => {
			if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );
		menu.addEventListener( 'click', ( e ) => {
			const link = e.target.closest( 'a' );
			if ( link && link.hash && link.pathname === window.location.pathname ) {
				setOpen( false );
			}
		} );
		window.matchMedia( '(min-width: 901px)' ).addEventListener( 'change', ( e ) => {
			if ( e.matches ) {
				setOpen( false );
			}
		} );
	}

	/* ---------- פס התקדמות גלילה בכותרת ---------- */
	const progress = document.getElementById( 'bw-progress' );
	if ( progress ) {
		let ticking = false;
		const update = () => {
			const max = html.scrollHeight - html.clientHeight;
			progress.style.width = ( max > 0 ? ( html.scrollTop / max ) * 100 : 0 ) + '%';
			ticking = false;
		};
		window.addEventListener( 'scroll', () => {
			if ( ! ticking ) {
				window.requestAnimationFrame( update );
				ticking = true;
			}
		}, { passive: true } );
		update();
	}

	/* ---------- הודעת מדיניות פרטיות (פעם אחת לכל דפדפן) ---------- */
	const privacy = document.getElementById( 'privacy-notice' );
	if ( privacy ) {
		const KEY = 'bw_privacy_ack';
		const hasCookie = document.cookie.split( ';' ).some( ( c ) => c.trim().indexOf( KEY + '=' ) === 0 );
		if ( ! store.get( KEY ) && ! hasCookie ) {
			privacy.hidden = false;
			const accept = privacy.querySelector( '[data-privacy-accept]' );
			const previouslyFocused = document.activeElement;
			const dismiss = () => {
				store.set( KEY, '1' );
				document.cookie = KEY + '=1; path=/; max-age=31536000; SameSite=Lax' + ( location.protocol === 'https:' ? '; Secure' : '' );
				privacy.hidden = true;
				if ( previouslyFocused && previouslyFocused !== document.body && previouslyFocused.focus ) {
					previouslyFocused.focus();
				}
			};
			if ( accept ) {
				accept.addEventListener( 'click', dismiss );
				accept.focus( { preventScroll: true } );
			}
			privacy.addEventListener( 'keydown', ( e ) => {
				if ( e.key === 'Escape' ) {
					dismiss();
				}
			} );
		}
	}

	/* ---------- נגישות: החלת העדפות שמורות + טעינה עצלה של הסרגל ---------- */
	const A11Y_KEY = 'bw_a11y';
	const a11yClasses = [ 'contrast', 'invert', 'grayscale', 'underline', 'readable', 'no-motion', 'headings', 'links', 'keyboard' ];
	const applyPrefs = ( prefs ) => {
		a11yClasses.forEach( ( c ) => html.classList.toggle( 'a11y-' + c, !! prefs[ c ] ) );
		[ 'a11y-font-1', 'a11y-font-2', 'a11y-font-3', 'a11y-font--1' ].forEach( ( c ) => html.classList.remove( c ) );
		const size = parseInt( prefs.font, 10 ) || 0;
		if ( size !== 0 ) {
			html.classList.add( 'a11y-font-' + size );
		}
	};
	let prefs = {};
	try {
		prefs = JSON.parse( store.get( A11Y_KEY ) || '{}' ) || {};
	} catch ( e ) {
		prefs = {};
	}
	applyPrefs( prefs );
	window.beckwealthA11y = {
		classes: a11yClasses,
		get: () => prefs,
		set( next ) {
			prefs = next;
			store.set( A11Y_KEY, JSON.stringify( prefs ) );
			applyPrefs( prefs );
		},
	};

	const a11yToggle = document.getElementById( 'a11y-toggle' );
	if ( a11yToggle && data.a11yScript ) {
		let loading = false;
		a11yToggle.addEventListener( 'click', () => {
			if ( window.beckwealthA11yPanel ) {
				window.beckwealthA11yPanel.open();
				return;
			}
			if ( loading ) {
				return;
			}
			loading = true;
			a11yToggle.setAttribute( 'aria-busy', 'true' );
			const script = document.createElement( 'script' );
			script.src = data.a11yScript;
			script.defer = true;
			script.onload = () => {
				a11yToggle.removeAttribute( 'aria-busy' );
				if ( window.beckwealthA11yPanel ) {
					window.beckwealthA11yPanel.open();
				}
			};
			script.onerror = () => {
				loading = false;
				a11yToggle.removeAttribute( 'aria-busy' );
			};
			document.body.appendChild( script );
		} );
	}
}() );
