/**
 * Site-wide scroll-reveal animation for hexnity-wp-child.
 *
 * Plain, hand-written, browser-native JS — no build step, matching
 * assets/css/src/site-theme.css's own "no build pipeline" approach.
 * Targets bare structural selectors that already exist in every
 * template (section/.hero/.card/.sec-head/etc.), so no template file
 * needs its markup edited to opt in — see the ".hex-reveal" CSS rules
 * in site-theme.css for the actual visual treatment.
 *
 * Progressive enhancement: nothing is hidden until this script adds
 * .hex-anim-ready to <html>, so content stays fully visible if this
 * file fails to load, is blocked, or the browser has no
 * IntersectionObserver support. Also does nothing at all when the
 * visitor has prefers-reduced-motion set.
 */
( function () {
	'use strict';

	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	var REVEAL_SELECTOR = [
		'section',
		'.hero',
		'.page-hero',
		'.brand-intro',
		'.card',
		'.path-card',
		'.sec-head',
		'.form-split',
		'.compliance-box',
		'.quote-block',
		'.accordion-item',
		'.stats-grid > div'
	].join( ', ' );

	function init() {
		var targets = document.querySelectorAll( REVEAL_SELECTOR );

		if ( ! targets.length ) {
			return;
		}

		document.documentElement.classList.add( 'hex-anim-ready' );

		if ( ! ( 'IntersectionObserver' in window ) ) {
			for ( var i = 0; i < targets.length; i++ ) {
				targets[ i ].classList.add( 'hex-in-view' );
			}
			return;
		}

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'hex-in-view' );
					observer.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' } );

		for ( var j = 0; j < targets.length; j++ ) {
			targets[ j ].classList.add( 'hex-reveal' );
			observer.observe( targets[ j ] );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
