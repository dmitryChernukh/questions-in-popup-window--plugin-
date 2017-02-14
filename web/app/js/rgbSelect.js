/**
 * Created by dmitry on 21.03.16.
 */
$( document ).ready(function() {

    $('.rgb-select').colorPicker({
        color: '#B700FF',
        customBG: '#B700FF',
        animationSpeed: 150,
        GPU: true,
        doRender: true | 'selector',
        opacity: true,
        renderCallback: function($elm, toggled) {},
        buildCallback: function($elm) {},
        css: '',
        cssAddon: '',
        margin: '',
        scrollResize: true,
        gap: 4,
        preventFocus: false,
        body: document.body // the element where the events are attached to (touchstart, mousedown, pointerdown, focus, click, change)
    });

});
