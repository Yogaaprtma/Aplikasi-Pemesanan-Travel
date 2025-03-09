document.addEventListener("DOMContentLoaded", function () {
    const travelTable = document.getElementById("travelTable");
    const visibleRecords = document.getElementById("visibleRecords");

    if (travelTable) {
        const tableRows = travelTable.querySelectorAll("tbody tr:not(#noDataRow)");
        const originalRowCount = tableRows.length;

        // Update jumlah data yang ditampilkan
        if (visibleRecords) {
            visibleRecords.textContent = originalRowCount;
        }
    }

    // Inisialisasi Bootstrap Tooltip jika tersedia
    if (typeof bootstrap !== "undefined" && typeof bootstrap.Tooltip !== "undefined") {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((tooltipTriggerEl) => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
