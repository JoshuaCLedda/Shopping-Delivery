$(document).ready(function () {
    // Initialize your DataTable first
    const table = $('#datatable').DataTable();

    // Append status filter to DataTables search container
    $('#datatable_filter').append(`
        <label class="ms-3 d-inline-flex align-items-center">
            <select id="statusFilter" class="form-select form-select-sm" style="min-width: 160px;">
                <option value="">All</option>
                <option value="Placed">Placed</option>
                <option value="Confirmed">Confirmed</option>
                <option value="In Process">In Process</option>
                <option value="Cancelled">Cancelled</option>
                <option value="Received">Received</option>
            </select>
        </label>
    `);

    // Filter logic based on visible status text (badge or plain text)
    $('#statusFilter').on('change', function () {
        const selected = $(this).val();
        if (selected) {
            table.column(2).search(selected, true, false).draw(); // Column 2 = Status
        } else {
            table.column(2).search('').draw();
        }
    });
});