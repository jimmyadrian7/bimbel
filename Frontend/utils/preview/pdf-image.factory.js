import { pdfToImage, saveBlob, FORMATS } from "./pdf-image.core";

(() => {
    "use strict";

    angular.module('app.utils')
        .factory('pdfImage', pdfImage);

    pdfImage.$inject = ['$q', 'logger'];

    /**
     * Export a PDF (base64, as the report endpoints return it) as an image:
     *   pdfImage.download(base64, 'kwitansi-KW-0001', { format: 'png' | 'jpg', dpi: 200 })
     * Resolves when the download has been started; on failure it shows a message and rejects.
     */
    function pdfImage($q, logger)
    {
        return {
            formats: FORMATS,
            toBlob: toBlob,
            download: download
        };

        function toBlob(base64, options)
        {
            return $q.when(pdfToImage(base64, options));
        }

        function download(base64, name, options)
        {
            const format = options && FORMATS[options.format] ? options.format : 'png';
            const filename = String(name || 'kwitansi').replace(/[\\/:*?"<>|\s]+/g, '-').replace(/\.(png|jpe?g|pdf)$/i, '');

            return toBlob(base64, options).then(blob => {
                saveBlob(blob, `${filename}.${FORMATS[format].ext}`);
            }).catch(error => {
                logger.error('Gambar tidak dapat dibuat. Gunakan tombol Cetak untuk mengunduh PDF.', error, 'Error');
                return $q.reject(error);
            });
        }
    }
})()
