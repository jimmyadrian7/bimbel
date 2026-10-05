/**
 * Draws one page of a PDF (base64) onto a canvas and returns it as a PNG or JPG Blob.
 *
 * Uses pdf.js, loaded on demand (webpack puts it in its own chunk, so the app does not get
 * heavier until someone exports an image). The image is exactly what the PDF shows, because
 * it is the dompdf output that is being drawn, not a second rendering of the template.
 */

export const FORMATS = {
    png: { mime: 'image/png', ext: 'png' },
    jpg: { mime: 'image/jpeg', ext: 'jpg' }
};

export const DEFAULT_DPI = 200;       // A4 landscape = 2339 x 1654 px
const MAX_DPI = 400;
const MAX_PIXELS = 36e6;              // keep the canvas within what browsers can allocate
const JPG_QUALITY = 0.92;

let pdfjsPromise = null;

function loadPdfjs()
{
    if (!pdfjsPromise)
    {
        // The worker "entry" registers the worker code in the page itself (no extra file to host).
        pdfjsPromise = Promise.all([
            import(/* webpackChunkName: "pdfjs" */ 'pdfjs-dist/legacy/build/pdf'),
            import(/* webpackChunkName: "pdfjs" */ 'pdfjs-dist/legacy/build/pdf.worker.entry')
        ]).then(modules => modules[0]).catch(error => {
            pdfjsPromise = null; // allow a retry (e.g. the chunk failed to download)
            throw error;
        });
    }

    return pdfjsPromise;
}

function toBytes(base64)
{
    // dompdf output is plain base64; tolerate a data: prefix and whitespace anyway.
    const clean = String(base64 || '').replace(/^data:[^,]*,/, '').replace(/\s+/g, '');
    const binary = atob(clean);
    const bytes = new Uint8Array(binary.length);

    for (let i = 0; i < binary.length; i++)
    {
        bytes[i] = binary.charCodeAt(i);
    }

    return bytes;
}

/**
 * @param {string} base64 the PDF
 * @param {{format?: 'png'|'jpg', dpi?: number, page?: number}} options
 * @returns {Promise<Blob>}
 */
export function pdfToImage(base64, options = {})
{
    const format = FORMATS[options.format] ? options.format : 'png';
    const dpi = Math.max(72, Math.min(MAX_DPI, Number(options.dpi) || DEFAULT_DPI));
    const pageNumber = Math.max(1, parseInt(options.page, 10) || 1);

    return loadPdfjs().then(pdfjs => {
        const task = pdfjs.getDocument({ data: toBytes(base64) });

        return task.promise.then(pdf => {
            return pdf.getPage(Math.min(pageNumber, pdf.numPages)).then(page => {
                let scale = dpi / 72; // PDF units are 1/72 inch
                const base = page.getViewport({ scale: 1 });

                if (base.width * base.height * scale * scale > MAX_PIXELS)
                {
                    scale = Math.sqrt(MAX_PIXELS / (base.width * base.height));
                }

                const viewport = page.getViewport({ scale: scale });
                const canvas = document.createElement('canvas');
                canvas.width = Math.ceil(viewport.width);
                canvas.height = Math.ceil(viewport.height);

                const context = canvas.getContext('2d');
                // PDF pages are transparent; without this JPG turns black and PNG is see-through.
                context.fillStyle = '#ffffff';
                context.fillRect(0, 0, canvas.width, canvas.height);

                return page.render({ canvasContext: context, viewport: viewport }).promise.then(() => {
                    return new Promise((resolve, reject) => {
                        canvas.toBlob(blob => {
                            if (blob) { resolve(blob); } else { reject(new Error('Gambar tidak dapat dibuat.')); }
                        }, FORMATS[format].mime, JPG_QUALITY);
                    });
                });
            });
        }).finally(() => task.destroy());
    });
}

/** Saves a Blob through a temporary link (works without any server round trip). */
export function saveBlob(blob, filename)
{
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.href = url;
    link.download = filename;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    setTimeout(() => URL.revokeObjectURL(url), 10000);
}
