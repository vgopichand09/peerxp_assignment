/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    var dataTable1 = jQuery('#myDataTable').DataTable();

    jQuery('.viewData').click(function (event) {
        event.preventDefault();
        jQuery('#subBut').val('Edit');
        jQuery('#pee_1').prop('readonly', true);
        jQuery('#pee_desc').css('display', 'none');
        var ticketId = jQuery(this).data('id');
        jQuery('#ticketId').val(ticketId);
        var rowData = dataTable1.rows('#' + ticketId).data();
        for (var i = 0; i < rowData[0].length - 1; i++) {
            if (!jQuery("#pee_" + i).length == 0) {
                jQuery("#pee_" + i).val(rowData[0][i]);
            }
        }
    });
    jQuery('#myModal').on('hidden.bs.modal', function () {
        jQuery('#subBut').val('Submit');
        jQuery('#pee_1').prop('readonly', false);
        jQuery('#pee_desc').css('display', 'block');
        document.getElementById("myForm").reset();
    });

});