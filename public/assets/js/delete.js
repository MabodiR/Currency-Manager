$(document).ready(function() {

    // Delete currency section
    var currencyIdToDelete;
    var clickedIcon;

    $('.delete-currency').on('click', function() {
        currencyIdToDelete = $(this).data('currency-id');
    
        clickedIcon = $(this); // Store the reference to $(this)
        $('#confirmDeleteModal').modal('show'); // Show the delete confirmation modal
    });

    $('#confirmDeleteBtn').on('click', function() {
       
    $('#deleteLoadingButton').removeClass('d-none');
    // Delay in milliseconds (2 seconds in this case)
      setTimeout(function () {
        $('#confirmDeleteModal').modal('hide'); // Hide the confirmation modal
        $.ajax({
            url: '/currencies/' + currencyIdToDelete,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Handle success response
                clickedIcon.closest('tr').remove(); // Use the stored reference to remove the table row
                $('#deleteLoadingButton').addClass('d-none');
            },
            error: function(xhr, status, error) {
                // Handle error response, if needed
                console.error(error);
            }
        });
      }, 2000);
    });
});