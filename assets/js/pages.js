/**
 * Beck Wealth – העמודים הפנימיים: Scroll reveal (עם מונים), אקורדיוני שאלות (כמה בעמוד),
 * ציר הזמן הנגלל (מי אנחנו) ושעונים מקומיים (יצירת קשר).
 * התנהגות זהה לקבצי העיצוב; מכבד prefers-reduced-motion ואת מתג "אנימציות".
 *
 * @package BeckWealth
 */
( function () {
	'use strict';

	const body = document.body;
	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches || document.documentElement.classList.contains( 'a11y-no-motion' );
	const motionOn = body.classList.contains( 'bw-motion' ) && ! reduce;
	const ease = 'cubic-bezier(.22,.7,.25,1)';

	/* ---------- ספירת מספרים (data-count) ---------- */
	const runCount = ( el ) => {
		if ( el._counted ) {
			return;
		}
		el._counted = true;
		const to = parseFloat( el.getAttribute( 'data-count' ) );
		if ( isNaN( to ) ) {
			return;
		}
		const dec = parseInt( el.getAttribute( 'data-decimals' ) || '0', 10 );
		const pre = el.getAttribute( 'data-prefix' ) || '';
		const suf = el.getAttribute( 'data-suffix' ) || '';
		const finalText = el.textContent;
		el.setAttribute( 'aria-label', finalText );
		const t0 = performance.now();
		const step = ( t ) => {
			const p = Math.min( ( t - t0 ) / 1500, 1 );
			const k = 1 - Math.pow( 1 - p, 3 );
			el.textContent = pre + ( to * k ).toFixed( dec ) + suf;
			if ( p < 1 ) {
				requestAnimationFrame( step );
			} else {
				el.textContent = finalText;
				el.removeAttribute( 'aria-label' );
			}
		};
		requestAnimationFrame( step );
	};

	/* ---------- Reveal בגלילה ---------- */
	const els = Array.from( document.querySelectorAll( '[data-reveal]' ) );

	if ( motionOn && 'IntersectionObserver' in window ) {
		els.forEach( ( el ) => {
			el.style.opacity = '0';
			el.style.transform = 'translateY(48px)';
			el.style.transition = 'opacity 1.1s ' + ease + ', transform 1.1s ' + ease + ', background .35s';
			el.querySelectorAll( '[data-diamond]' ).forEach( ( x ) => {
				x.style.transition = 'transform .7s cubic-bezier(.34,1.45,.45,1) .25s';
				x.style.transform = 'rotate(0deg) scale(0)';
			} );
		} );

		const io = new IntersectionObserver( ( entries ) => {
			let i = 0;
			entries.forEach( ( e ) => {
				if ( ! e.isIntersecting ) {
					return;
				}
				const el = e.target;
				window.setTimeout( () => {
					el.style.opacity = '1';
					el.style.transform = 'none';
					el.querySelectorAll( '[data-diamond]' ).forEach( ( x ) => {
						x.style.transform = 'rotate(45deg) scale(1)';
					} );
					if ( el.hasAttribute( 'data-count' ) ) {
						runCount( el );
					}
					el.querySelectorAll( '[data-count]' ).forEach( runCount );
				}, Math.min( i++ * 160, 640 ) );
				io.unobserve( el );
			} );
		}, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' } );

		els.forEach( ( el ) => io.observe( el ) );
	}

	/* ---------- אקורדיוני שאלות ותשובות (פריט אחד פתוח בכל רשימה) ---------- */
	document.querySelectorAll( '.faq__list' ).forEach( ( faq ) => {
		const buttons = Array.from( faq.querySelectorAll( '.faq__btn' ) );
		const setOpen = ( btn, open ) => {
			const panel = document.getElementById( btn.getAttribute( 'aria-controls' ) );
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			const sym = btn.querySelector( '.faq__sym' );
			if ( sym ) {
				sym.textContent = open ? '−' : '+';
			}
			if ( panel ) {
				panel.hidden = ! open;
			}
		};
		buttons.forEach( ( btn ) => {
			btn.addEventListener( 'click', () => {
				const isOpen = btn.getAttribute( 'aria-expanded' ) === 'true';
				buttons.forEach( ( b ) => setOpen( b, false ) );
				if ( ! isOpen ) {
					setOpen( btn, true );
				}
			} );
		} );
	} );

	/* ---------- ציר הזמן (מי אנחנו): גלילה אופקית עם חצים ופס התקדמות ---------- */
	const track = document.getElementById( 'tl-track' );
	if ( track ) {
		const bar = document.getElementById( 'tl-bar' );
		const updateBar = () => {
			if ( ! bar ) {
				return;
			}
			const m = track.scrollWidth - track.clientWidth;
			const pct = m > 0 ? Math.abs( track.scrollLeft ) / m : 0;
			const visible = track.scrollWidth > 0 ? track.clientWidth / track.scrollWidth : 1;
			bar.style.width = ( visible + pct * ( 1 - visible ) ) * 100 + '%';
		};
		const scrollTrack = ( dir ) => {
			// ב-RTL scrollLeft שלילי: "הבא" (←) מתקדם לכיוון ההפוך של הציר.
			track.scrollBy( { left: dir * 536, behavior: motionOn ? 'smooth' : 'auto' } );
		};
		track.addEventListener( 'scroll', updateBar, { passive: true } );
		window.addEventListener( 'resize', updateBar );
		document.querySelectorAll( '[data-tl-prev]' ).forEach( ( b ) => b.addEventListener( 'click', () => scrollTrack( 1 ) ) );
		document.querySelectorAll( '[data-tl-next]' ).forEach( ( b ) => b.addEventListener( 'click', () => scrollTrack( -1 ) ) );
		track.addEventListener( 'keydown', ( e ) => {
			if ( e.key === 'ArrowLeft' || e.key === 'ArrowRight' ) {
				e.preventDefault();
				scrollTrack( e.key === 'ArrowLeft' ? -1 : 1 );
			}
		} );

		/* גרירה: פס ההתקדמות משמש כ"סקראבר", והציר עצמו נגרר בעכבר (במגע – גלילה טבעית). */
		const isRtl = () => 'rtl' === window.getComputedStyle( track ).direction;
		const maxScroll = () => Math.max( 0, track.scrollWidth - track.clientWidth );
		const setScrollPct = ( pct ) => {
			pct = Math.max( 0, Math.min( 1, pct ) );
			track.scrollLeft = ( isRtl() ? -1 : 1 ) * pct * maxScroll();
		};
		const progress = bar ? bar.parentElement : null;
		if ( progress ) {
			let scrubbing = false;
			const pctFromEvent = ( e ) => {
				const r = progress.getBoundingClientRect();
				const x = r.width > 0 ? ( e.clientX - r.left ) / r.width : 0;
				return isRtl() ? 1 - x : x;
			};
			const endScrub = () => {
				if ( ! scrubbing ) {
					return;
				}
				scrubbing = false;
				progress.classList.remove( 'is-dragging' );
				track.style.scrollSnapType = '';
			};
			progress.addEventListener( 'pointerdown', ( e ) => {
				if ( e.button !== 0 && e.pointerType === 'mouse' ) {
					return;
				}
				scrubbing = true;
				progress.classList.add( 'is-dragging' );
				track.style.scrollSnapType = 'none';
				progress.setPointerCapture( e.pointerId );
				setScrollPct( pctFromEvent( e ) );
				e.preventDefault();
			} );
			progress.addEventListener( 'pointermove', ( e ) => {
				if ( scrubbing ) {
					setScrollPct( pctFromEvent( e ) );
				}
			} );
			progress.addEventListener( 'pointerup', endScrub );
			progress.addEventListener( 'pointercancel', endScrub );
			progress.addEventListener( 'lostpointercapture', endScrub );
		}

		let grabbing = false;
		let grabX = 0;
		let grabScroll = 0;
		track.addEventListener( 'pointerdown', ( e ) => {
			if ( e.pointerType !== 'mouse' || e.button !== 0 ) {
				return;
			}
			grabbing = true;
			grabX = e.clientX;
			grabScroll = track.scrollLeft;
			track.classList.add( 'is-grabbing' );
			track.style.scrollSnapType = 'none';
		} );
		window.addEventListener( 'pointermove', ( e ) => {
			if ( grabbing ) {
				track.scrollLeft = grabScroll - ( e.clientX - grabX );
			}
		} );
		const endGrab = () => {
			if ( ! grabbing ) {
				return;
			}
			grabbing = false;
			track.classList.remove( 'is-grabbing' );
			track.style.scrollSnapType = '';
		};
		window.addEventListener( 'pointerup', endGrab );
		window.addEventListener( 'pointercancel', endGrab );
		window.addEventListener( 'blur', endGrab );

		updateBar();
	}

	/* ---------- שעונים מקומיים (יצירת קשר): עדכון כל 20 שניות ---------- */
	const clocks = Array.from( document.querySelectorAll( '[data-clock]' ) );
	if ( clocks.length && window.Intl && Intl.DateTimeFormat ) {
		const formats = {};
		const tick = () => {
			const now = new Date();
			clocks.forEach( ( el ) => {
				const tz = el.getAttribute( 'data-clock' );
				try {
					if ( ! formats[ tz ] ) {
						formats[ tz ] = new Intl.DateTimeFormat( 'en-GB', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: tz } );
					}
					el.textContent = formats[ tz ].format( now );
					el.setAttribute( 'datetime', now.toISOString() );
				} catch ( err ) {
					// אזור זמן לא נתמך – נשאיר את הערך מהשרת.
				}
			} );
		};
		tick();
		window.setInterval( tick, 20000 );
	}
}() );
