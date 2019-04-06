var Appraisal = {

    appraisalButtonClick: function() {
        Appraisal.oPopup = Dialog.info($('appraisal_popup_content_html').innerHTML, {
            id:'appraisal_popup',
            draggable: true,
            resizable: true,
            closable: true,
            className: "magento",
            windowClassName: "popup-window",
            title: 'PDF Settings',
            top: 50,
            width: 700,
            height: 280,
            zIndex: 100,
            hideEffect: Element.hide,
            showEffect: Element.show
        });
    },

    formSubmitButtonClick: function() {
        Appraisal.form.submit();
    }
}

Event.observe(window, "load", function() {
    $('appraisal_open_popup_button').observe('click', Appraisal.appraisalButtonClick);

    $$('.content-header .form-buttons')[0].insert($('appraisal_open_popup_button'));

    Appraisal.form = new varienForm('appraisal_pdf_settings_form', '<?php echo $this->getValidationUrl(); ?>');
});