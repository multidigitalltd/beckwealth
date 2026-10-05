/**
 * Beck Wealth – מסך "ניהול תוכן": רשימות עם הוספה/הסרה/סידור, בחירת תמונות, חיפוש שדות
 * ואזהרה על שינויים שלא נשמרו.
 *
 * @package BeckWealth
 */
( function ( $ ) {
	'use strict';

	const i18n = ( window.beckwealthContent && window.beckwealthContent.i18n ) || {};
	const form = document.getElementById( 'bw-content-form' );
	let dirty = false;

	const markDirty = ( el ) => {
		dirty = true;
		const f = el && el.closest ? el.closest( '.bw-f' ) : null;
		if ( f ) {
			f.classList.add( 'is-changed' );
		}
		const status = document.querySelector( '.bw-savebar__status' );
		if ( status ) {
			status.textContent = i18n.unsaved || '';
		}
	};

	/* ---------- גובה אוטומטי לתאים ארוכים ---------- */
	const autosize = ( ta ) => {
		ta.style.height = 'auto';
		ta.style.height = ta.scrollHeight + 2 + 'px';
	};

	/* ---------- רשימות ---------- */
	const serialize = ( list ) => {
		const value = list.querySelector( '.bw-list__value' );
		const rows = [];
		list.querySelectorAll( 'tbody tr' ).forEach( ( tr ) => {
			const cells = Array.from( tr.querySelectorAll( '.bw-cell' ) ).map( ( c ) => c.value.replace( /\|/g, '/' ).replace( /\r?\n/g, ' ' ).trim() );
			while ( cells.length > 1 && cells[ cells.length - 1 ] === '' ) {
				cells.pop();
			}
			if ( cells.join( '' ) !== '' ) {
				rows.push( cells.join( '|' ) );
			}
		} );
		value.value = rows.join( '\n' );
	};
	const renumber = ( list ) => {
		list.querySelectorAll( 'tbody tr' ).forEach( ( tr, i ) => {
			tr.querySelector( '.bw-list__n' ).textContent = String( i + 1 );
		} );
		list.classList.toggle( 'bw-list--empty', ! list.querySelector( 'tbody tr' ) );
	};
	const bindRow = ( list, tr ) => {
		tr.querySelectorAll( 'textarea.bw-cell' ).forEach( ( ta ) => {
			autosize( ta );
			ta.addEventListener( 'input', () => autosize( ta ) );
		} );
		tr.querySelectorAll( '.bw-cell' ).forEach( ( c ) => c.addEventListener( 'input', () => { serialize( list ); markDirty( c ); } ) );
		tr.querySelector( '[data-row-up]' ).addEventListener( 'click', () => {
			const prev = tr.previousElementSibling;
			if ( prev ) {
				tr.parentNode.insertBefore( tr, prev );
				renumber( list ); serialize( list ); markDirty( tr );
			}
		} );
		tr.querySelector( '[data-row-down]' ).addEventListener( 'click', () => {
			const next = tr.nextElementSibling;
			if ( next ) {
				tr.parentNode.insertBefore( next, tr );
				renumber( list ); serialize( list ); markDirty( tr );
			}
		} );
		tr.querySelector( '[data-row-del]' ).addEventListener( 'click', () => {
			const parent = tr.parentNode;
			tr.remove();
			renumber( list ); serialize( list ); markDirty( parent );
		} );
	};
	document.querySelectorAll( '.bw-list' ).forEach( ( list ) => {
		list.querySelectorAll( 'tbody tr' ).forEach( ( tr ) => bindRow( list, tr ) );
		renumber( list );
		list.querySelector( '.bw-list__add' ).addEventListener( 'click', () => {
			const tpl = list.querySelector( '.bw-list__tpl' );
			const tr = tpl.content.firstElementChild.cloneNode( true );
			list.querySelector( 'tbody' ).appendChild( tr );
			bindRow( list, tr );
			renumber( list ); serialize( list ); markDirty( tr );
			const first = tr.querySelector( '.bw-cell' );
			if ( first ) {
				first.focus();
			}
		} );
	} );
	document.querySelectorAll( '.bw-textarea' ).forEach( ( ta ) => {
		ta.addEventListener( 'input', () => markDirty( ta ) );
	} );

	/* ---------- תמונות (ספריית המדיה) ---------- */
	document.querySelectorAll( '.bw-image' ).forEach( ( box ) => {
		const id = box.querySelector( '.bw-image__id' );
		const preview = box.querySelector( '.bw-image__preview' );
		const choose = box.querySelector( '.bw-image__choose' );
		const remove = box.querySelector( '.bw-image__remove' );
		let frame = null;
		choose.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			if ( ! window.wp || ! wp.media ) {
				return;
			}
			if ( ! frame ) {
				frame = wp.media( { title: i18n.choose || 'Choose image', button: { text: i18n.use || 'Use' }, library: { type: 'image' }, multiple: false } );
				frame.on( 'select', () => {
					const att = frame.state().get( 'selection' ).first().toJSON();
					const url = ( att.sizes && ( att.sizes.medium || att.sizes.full ) ) ? ( att.sizes.medium || att.sizes.full ).url : att.url;
					id.value = att.id;
					preview.innerHTML = '';
					const img = document.createElement( 'img' );
					img.src = url;
					img.alt = '';
					preview.appendChild( img );
					box.classList.add( 'has-image' );
					remove.hidden = false;
					markDirty( box );
				} );
			}
			frame.open();
		} );
		remove.addEventListener( 'click', () => {
			id.value = '0';
			preview.innerHTML = '';
			box.classList.remove( 'has-image' );
			remove.hidden = true;
			markDirty( box );
		} );
	} );

	/* ---------- שדות רגילים ---------- */
	if ( form ) {
		form.querySelectorAll( 'input.bw-input, select.bw-input, .bw-switch input' ).forEach( ( el ) => {
			el.addEventListener( 'change', () => markDirty( el ) );
			el.addEventListener( 'input', () => markDirty( el ) );
		} );
		form.addEventListener( 'submit', () => {
			dirty = false;
			document.querySelectorAll( '.bw-list' ).forEach( serialize );
		} );
		window.addEventListener( 'beforeunload', ( e ) => {
			if ( dirty ) {
				e.preventDefault();
				e.returnValue = '';
			}
		} );
	}

	/* ---------- חיפוש שדות ---------- */
	const search = document.getElementById( 'bw-content-search' );
	if ( search && form ) {
		const nomatch = form.querySelector( '.bw-content__nomatch' );
		const apply = () => {
			const q = search.value.trim().toLowerCase();
			let visible = 0;
			form.querySelectorAll( '.bw-section' ).forEach( ( sec ) => {
				let any = false;
				sec.querySelectorAll( '.bw-f' ).forEach( ( f ) => {
					const hit = ! q || ( f.getAttribute( 'data-search' ) || '' ).indexOf( q ) !== -1 || ( sec.querySelector( '.bw-section__title' ).textContent || '' ).toLowerCase().indexOf( q ) !== -1;
					f.classList.toggle( 'is-hidden', ! hit );
					if ( hit ) {
						any = true; visible++;
					}
				} );
				sec.classList.toggle( 'is-hidden', ! any );
				if ( q && any ) {
					sec.open = true;
				}
			} );
			if ( nomatch ) {
				nomatch.hidden = visible > 0;
				nomatch.textContent = i18n.noMatch || '';
			}
		};
		search.addEventListener( 'input', apply );
	}
}( window.jQuery ) );
