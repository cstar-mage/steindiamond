/*

   Utility script to integrate Magic Toolbox tools with nopCommerce 2
   Copyright 2015 Magic Toolbox
   https://www.magictoolbox.com/nopcommerce/

*/
function MagicToolbox_addCSSRule(selector, declaration) {
    var ua = navigator.userAgent.toLowerCase();

    var isIE = (/msie/.test(ua)) && !(/opera/.test(ua)) && (/win/.test(ua)) && (!/msie 9\.0/.test(ua)) && (!/msie 10\.0/.test(ua));

    var style_node = document.createElement("style");
    style_node.setAttribute("type", "text/css");
    style_node.setAttribute("media", "screen");

    if (!isIE) style_node.appendChild(document.createTextNode(selector + " {" + declaration + "}"));

    document.getElementsByTagName("head")[0].appendChild(style_node);

    if (isIE && document.styleSheets && document.styleSheets.length > 0) {
        var last_style_node = document.styleSheets[document.styleSheets.length - 1];
        if (typeof(last_style_node.addRule) == "object") last_style_node.addRule(selector, declaration);
    }
}

function MagicToolbox_getLargeImage(src) {
    return src.replace(/^(.*)_[0-9]{1,}(\.[a-z]{1,})$/gm,"$1$2");
}

$mjs(document)[(typeof $mjs(document).jAddEvent)=='function'?'jAddEvent':'je1']('domready', function() {

    var MagicToolbox_mainImage = true,
        MagicToolbox_firstSelector = true,
        MagicToolbox_thumbSize,

        $MAGICJS = (typeof magicJS === 'undefined') ? $J : magicJS;


    if ('MagicZoomPlus' === MagicToolbox_toolName) {
        MagicToolbox_toolName = 'MagicZoom';
    }

    $mjs( $MAGICJS.$A($mjs(document).byTag('div')).filter(function(o){
        if (o.className=='gallery') {
            return true;
        }
    }) ).forEach(function(o) {

        $mjs( $MAGICJS.$A($mjs(o).byTag('img')) ).forEach(function(o) {
            if (MagicToolbox_mainImage) {


                var ael = document.createElement('A');
                ael.href = MagicToolbox_getLargeImage(o.src);

                MagicToolbox_thumbSize = parseInt(o.src.replace(/^.*_([0-9]{1,})\.[a-z]{1,}$/gm,"$1"));

                MagicToolbox_addCSSRule('.'+MagicToolbox_toolName+' img ', 'max-width:'+MagicToolbox_thumbSize+'px');

                ael.className = MagicToolbox_toolName;
                ael.setAttribute('title', o.getAttribute('alt'));
                ael.setAttribute('id', 'MagicImage');
                var iel = document.createElement('IMG');
                iel.setAttribute('src', o.src);
                iel.setAttribute('alt', o.getAttribute('alt'));
                ael.appendChild(iel);

                o.parentNode.replaceChild(ael,o);

                MagicToolbox_mainImage = false;
            } else {
                if ( 'MagicZoom' == MagicToolbox_toolName ) {
                    o.parentNode.setAttribute('data-image', MagicToolbox_getLargeImage(o.src) );
                    o.parentNode.setAttribute('data-zoom-id', 'MagicImage');
                } else if ( 'MagicThumb' == MagicToolbox_toolName ) {
                    o.parentNode.setAttribute('rev', MagicToolbox_getLargeImage(o.src) );
                    o.parentNode.setAttribute('rel', 'thumb-id: MagicImage');
                }
            }
        });

    });

    switch(MagicToolbox_toolName) {
        case 'MagicZoom': MagicZoom.refresh(); break;
        case 'MagicThumb': MagicThumb.refresh(); break;
    }

});

window['mgctlbx$Pltm'] = 'nopCommerce';

if (typeof($.fn.slimbox)!='undefined') $.fn.slimbox = function() {};
if (typeof($.fn.magnificPopup)!='undefined') $.fn.magnificPopup = function() {};
