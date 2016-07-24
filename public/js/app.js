function filterNodeListById( nodeList, id ) {

    var elements = Array.prototype.slice.call( nodeList );

    for (var i = elements.length - 1; i >= 0; i--) {
        if( elements[i].id == id ) {
            return elements[i];
        }
    }

}

// Clipboard.js
if( document.querySelectorAll( '.copy-button' ).length > 0 ) {

    var clipboard = new Clipboard( '.copy-button' );
    var clipboardTimeout = 2000;

    clipboard.on( 'success', function( event ) {
        var errorElement = filterNodeListById( event.trigger.parentNode.childNodes, 'copy-success' );

        errorElement.className += " visible";

        window.setTimeout( function() {
            errorElement.className = errorElement.className.replace(/\bvisible\b/,'');
        }, clipboardTimeout );
    } );

    clipboard.on( 'error', function( event ) {
        var errorElement = filterNodeListById( event.trigger.parentNode.childNodes, 'copy-error' );
        var osClass;

        if( /iPhone|iPad/i.test(navigator.userAgent) ) {
            osClass = 'mobile';
        } else if ( /Mac/i.test(navigator.userAgent) ) {
            osClass = 'macos';
        } else if ( /Windows/i.test(navigator.userAgent) || /X11/i.test(navigator.userAgent) ) {
            osClass = 'pc';
        } else {
            osClass = 'other';
        }

        errorElement.className += " visible " + osClass;

        window.setTimeout( function() {
            errorElement.className = errorElement.className.replace(/\bvisible\b/,'');
        }, clipboardTimeout );
    } );

}
