function Modal_window(params_obj) {

    if (!params_obj) params_obj = {};

    let scope = this;

    this.params = {
        heading: params_obj.heading !== undefined ? params_obj.heading : 'Заголовок модального окна',
        body: params_obj.body !== undefined ? params_obj.body : '<p>Контент модального окна</p>',
        class: params_obj.class !== undefined ? params_obj.class : 'common',
        additional_classes: params_obj.additional_classes !== undefined ? params_obj.additional_classes : '',
        max_width: params_obj.max_width !== undefined ? params_obj.max_width : 500,
    };



    console.log(this.params)

    this.wrapper = $('<div class="bp_modal_wrapper"></div>');
    this.modal = $('<div class="bp_modal '+ this.params.additional_classes +'"></div>');


    this.heading = $('<div class="bp_modal_heading"><h4>'+ this.params.heading +'</h4></div>');

    this.body = $('<div class="bp_modal_body"></div>');

    this.body.append(this.params.body);

    this.footer = $('<div class="bp_modal_footer"></div>');

    if(this.params.class === 'common'){
        this.ok_button = $('<div class="bp_button">Ок</div>');
        this.footer.append(this.ok_button);
        this.ok_button.click(function () {
            scope.close();
        })
    }

    if(this.params.class === 'yesno'){
        this.yes_button = $('<div class="bp_button">Да</div>');
        this.no_button = $('<div class="bp_button bp_button_red">Нет</div>');
        this.footer.append(this.yes_button);
        this.footer.append(this.no_button);
    }

    if(this.params.class === 'checkbox'){
        this.checkbox = $('<input type="checkbox"><label style="margin-left: 5px">Больше не показывать</label>');
        this.accept_button =  $('<div class="bp_button">Принять</div>');
        let div = $('<div></div>');
        div.append(this.checkbox);
        div.append(this.accept_button);
        this.footer.append(div);
        this.footer.css('text-align', 'left');
        this.accept_button.css('float', 'right')

    }

    this.body.css('max-height', $(window).height() - 280);


    this.wrapper.mousedown(function(e) {
        var clicked = $(e.target);
        if (clicked.is('.bp_modal') || clicked.parents().is('.bp_modal')) {
        } else {
            scope.close();
        }
    });

    this.close_button = $('<i class="glyphicon glyphicon-remove bp_close"></i>');

    this.modal.append(this.close_button);
    this.modal.append(this.heading);
    this.modal.append(this.body);
    this.modal.append(this.footer);

    this.wrapper.append(this.modal);


    this.modal.css('max-width',this.params.max_width);

    this.close_button.click(function () {
        scope.close();
    })

}

Modal_window.prototype.constructor = Modal_window;

Modal_window.prototype.show = function () {
    var scope = this;

    $('body').append(this.wrapper);
    scope.wrapper.fadeIn();

};

Modal_window.prototype.close = function () {
    var scope = this;

    this.wrapper.fadeOut(300,function () {
        scope.wrapper.remove();
    })

};


$(document).ready(function () {
    $('.help_icon').click(function () {
        let help_content = $('.' + $(this).attr('data-target'));
        let modal = new Modal_window({
            heading: help_content.find('.help_heading').html(),
            body: help_content.find('.help_content').html(),
            max_width: 768
        });
        modal.show();
    });
})

