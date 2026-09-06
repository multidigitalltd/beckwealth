/**
 * Beck Wealth – דף הבית בלבד: Scroll reveal, ספירת מספרים, אקורדיון שאלות.
 * התנהגות זהה לקובץ העיצוב v6. מכבד prefers-reduced-motion ואת הדגל motion.
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
			el.style.transition = 'opacity 1.1s ' + ease + ', transform 1.1s ' + ease + ', background .25s';
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
		}, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' } );

		els.forEach( ( el ) => io.observe( el ) );
	}

	/* ---------- אקורדיון שאלות ותשובות (פריט אחד פתוח) ---------- */
	const faq = document.querySelector( '.faq__list' );
	if ( faq ) {
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
	}
}() );
