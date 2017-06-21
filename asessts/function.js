/**
 * Created by skay_ on 13/06/2017.
 */

function notify($type,$text) {
    'use strict';

    var notice = new PNotify({
        title:$text.title,
        text:$text.text,
        type:$type,
        styling: 'fontawesome',
        buttons:{
            labels: {close: "Fechar", stick: "Fixar",unstick:"Desfixar"}
        }
    });

    notice.get().click(function() {
        notice.remove();
    })
}

function spinnerStart($ELEMENT) {
    'use strict';

    $($ELEMENT)
        .addClass('sobreposicao')
        .html(
            '<div class="spinner2"></div>' +
            '<p class="spinnerText"">Aguardando Resposta</p>'+
            '<div class="clearfix"></div>'
        );
}

function spinnerDestroy($ELEMENT) {
    'use strict';
    $($ELEMENT).removeClass('sobreposicao').html('').fadeOut();
}