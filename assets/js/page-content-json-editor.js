/**
 * Upgrades the "Page Content (JSON)" textarea (#hex_page_content_json) on
 * the Edit Page screen into a CodeMirror-based code editor, using the
 * settings WordPress core's own wp_enqueue_code_editor() already prepared
 * (see hexnity_wp_child_enqueue_page_content_code_editor() in
 * functions.php). Falls back silently to the plain textarea if the field
 * isn't on the page, or if wp.codeEditor/jQuery/the localized settings
 * aren't available for any reason -- this must never block saving.
 *
 * IMPORTANT: CodeMirror.fromTextArea() (what wp.codeEditor.initialize()
 * wraps) does NOT keep the original <textarea> in sync on its own -- it
 * only writes its content back via an explicit .save() call. Without
 * wiring that up, edits made in the visual editor never reach the
 * underlying field that actually gets POSTed, so Update/Publish would
 * silently save the OLD value. Mirrors the pattern WordPress core's own
 * Additional CSS editor uses: sync on every change, plus a submit-time
 * safety net.
 */
( function( $ ) {
	'use strict';

	if ( 'undefined' === typeof wp || ! wp.codeEditor || 'undefined' === typeof hexnityWpChildJsonEditor ) {
		return;
	}

	$( function() {
		var textarea = document.getElementById( 'hex_page_content_json' );
		if ( ! textarea ) {
			return;
		}

		var editor = wp.codeEditor.initialize( textarea, hexnityWpChildJsonEditor.settings );
		if ( ! editor || ! editor.codemirror ) {
			return;
		}

		editor.codemirror.setSize( '100%', 480 );

		editor.codemirror.on( 'change', function() {
			editor.codemirror.save();
		} );

		$( textarea ).closest( 'form' ).on( 'submit', function() {
			editor.codemirror.save();
		} );
	} );
} )( jQuery );
