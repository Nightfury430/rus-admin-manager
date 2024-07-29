/**
 * @author alteredq / http://alteredqualia.com/
 * @author mr.doob / http://mrdoob.com/
 */

var WEBGL = {

    isWebGLAvailable: function () {

        try {

            var canvas = document.createElement( 'canvas' );
            return !! ( window.WebGLRenderingContext && ( canvas.getContext( 'webgl' ) || canvas.getContext( 'experimental-webgl' ) ) );

        } catch ( e ) {

            return false;

        }

    },

    isWebGL2Available: function () {

        try {

            var canvas = document.createElement( 'canvas' );
            return !! ( window.WebGL2RenderingContext && canvas.getContext( 'webgl2' ) );

        } catch ( e ) {

            return false;

        }

    },

    getWebGLErrorMessage: function () {

        return this.getErrorMessage( 1 );

    },

    getWebGL2ErrorMessage: function () {

        return this.getErrorMessage( 2 );

    },

    getErrorMessage: function ( version ) {

        var names = {
            1: 'WebGL',
            2: 'WebGL 2'
        };

        var contexts = {
            1: window.WebGLRenderingContext,
            2: window.WebGL2RenderingContext
        };

        var message = '$0 Не поддерживает <a href="https://ru.wikipedia.org/wiki/WebGL" style="color:#000; text-decoration: underline">$1</a>';

        var element = document.createElement( 'div' );
        element.id = 'webglmessage';
        // element.style.fontFamily = 'monospace';
        element.style.fontSize = '15px';
        element.style.fontWeight = 'normal';
        element.style.textAlign = 'center';
        element.style.background = '#fff';
        element.style.color = '#000';
        element.style.padding = '1.5em';
        element.style.width = '600px';
        element.style.margin = '5em auto 0';

        if ( contexts[ version ] ) {

            message = message.replace( '$0', 'Ваша видеокарта' );
            message +='<p>Для запуска необходима видеокарта с поддержкой OpenGL 2.0ES</p>'

        } else {

            message = message.replace( '$0', 'Ваш браузер' );

        }

        message = message.replace( '$1', names[ version ] );

        element.innerHTML = message;

        return element;

    }

};