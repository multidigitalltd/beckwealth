/**
 * Beck Wealth – טופס יצירת קשר: ולידציה נגישה בצד הלקוח.
 * הבדיקה המחייבת מתבצעת תמיד בצד השרת; כאן רק חוויית משתמש.
 * נטען רק בעמודים שבהם הטופס מוצג.
 *
 * @package BeckWealth
 */
( function () {
	'use strict';

	const i18n = ( window.beckwealthForm && window.beckwealthForm.i18n ) || {};

	document.querySelectorAll( '.contact-form' ).forEach( ( form ) => {
		const live = form.querySelector( '.form-live' );

		const setError = ( field, message ) => {
			const wrap = field.closest( '.form-field' );
			if ( ! wrap ) {
				return;
			}
			let err = wrap.querySelector( '.field-error' );
			if ( message ) {
				if ( ! err ) {
					err = document.createElement( 'span' );
					err.className = 'field-error';
					err.id = field.id + '-error';
					wrap.appendChild( err );
				}
				err.textContent = message;
				field.setAttribute( 'aria-invalid', 'true' );
				field.setAttribute( 'aria-describedby', err.id );
			} else if ( err ) {
				err.remove();
				field.removeAttribute( 'aria-invalid' );
				field.removeAttribute( 'aria-describedby' );
			}
		};

		const messageFor = ( field ) => {
			if ( field.validity.valueMissing ) {
				return i18n.required || 'This field is required.';
			}
			if ( field.type === 'email' && field.validity.typeMismatch ) {
				return i18n.email || 'Please enter a valid email address.';
			}
			if ( field.type === 'tel' && field.validity.patternMismatch ) {
				return i18n.phone || 'Please enter a valid phone number.';
			}
			return i18n.invalid || 'Please check this field.';
		};

		form.querySelectorAll( 'input, textarea, select' ).forEach( ( field ) => {
			field.addEventListener( 'blur', () => {
				setError( field, field.checkValidity() ? '' : messageFor( field ) );
			} );
			field.addEventListener( 'input', () => {
				if ( field.getAttribute( 'aria-invalid' ) === 'true' && field.checkValidity() ) {
					setError( field, '' );
				}
			} );
		} );

		form.addEventListener( 'submit', ( e ) => {
			let firstInvalid = null;
			form.querySelectorAll( 'input, textarea, select' ).forEach( ( field ) => {
				const valid = field.checkValidity();
				setError( field, valid ? '' : messageFor( field ) );
				if ( ! valid && ! firstInvalid ) {
					firstInvalid = field;
				}
			} );

			if ( firstInvalid ) {
				e.preventDefault();
				firstInvalid.focus();
				if ( live ) {
					live.textContent = i18n.fix || 'Please fix the highlighted fields.';
				}
				return;
			}

			const btn = form.querySelector( 'button[type="submit"]' );
			if ( btn ) {
				btn.disabled = true;
				btn.setAttribute( 'aria-busy', 'true' );
			}
			if ( live ) {
				live.textContent = i18n.sending || 'Sending…';
			}
		} );
	} );

	// אחרי הפניה חזרה: מיקוד להודעת הסטטוס.
	if ( window.location.search.indexOf( 'contact=' ) !== -1 ) {
		const notice = document.querySelector( '.form-notice' );
		if ( notice ) {
			notice.setAttribute( 'tabindex', '-1' );
			notice.focus();
		}
	}
}() );
