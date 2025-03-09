document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('downloadPdf').addEventListener('click', function () {
        const options = {
            margin: [10, 10],
            filename: 'invoice-' + document.getElementById('invoice-id').textContent + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        const element = document.getElementById('invoice-content');

        html2pdf().from(element).set(options).save();
    });
});