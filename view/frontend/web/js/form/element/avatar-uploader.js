define([
    'Magento_Ui/js/form/element/file-uploader'
], function (Element) {
    'use strict';

    return Element.extend({
        defaults: {
            fileInputName: '',
            fileInputId: '',
            imagePreviewId: '',
            originalAvatarUrl: '',
            originalAttributeValue: '',
            previewTmpl: 'GhoSter_CustomerAvatar/form/element/uploader/preview.html',
            fileUploadButtonName: 'file-uploader-button'
        },

        /**
         * Handler of the file upload complete event.
         *
         * @param {Event} e
         * @param {Object} data
         */
        onFileUploaded: function (e, data) {
            this._super(e, data);

            if (this.fileInputId !== '') {
                document.getElementById(this.fileInputId).value = data.result.file;
                document.getElementById(this.imagePreviewId).src = data.result.url;
            }
        },

        /**
         * Removes provided file from thes files list.
         *
         * @param {Object} file
         * @returns {FileUploader} Chainable.
         */
        removeFile: function (file) {
            if (this.fileInputId !== '') {
                document.getElementById(this.fileInputId).value = this.originalAttributeValue;
                document.getElementById(this.imagePreviewId).src = this.originalAvatarUrl;
            }

            return this._super(file);
        },

        /**
         * Trigger browse file
         */
        triggerBrowse: function () {
            document.getElementsByName(this.fileUploadButtonName)[0].click();
        }
    });
});
