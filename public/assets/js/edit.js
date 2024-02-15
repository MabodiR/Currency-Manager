$(document).ready(function () {
    $('.edit.alert').hide();
    $('.success-message').hide();
    // Function to fetch currency data by ID
    function getCurrencyDataById(currencyId) {
        $.ajax({
            url: '/currencies/' + currencyId,
            type: 'GET',
            success: function (response) {
                var currencyData = response.currency;
                // Set values in the edit modal
                $('#editCurrencyId').val(currencyData.id);
                $('#editName').val(currencyData.name);
                $('#editCode').val(currencyData.code);
                // Show the edit modal
                $('#editCurrencyModal').modal('show');
            },
            error: function (xhr, status, error) {
                // Handle the error response here
                console.log(xhr.responseText);
            }
        });
    }

    // Show the modal when the "Edit Currency" button is clicked
    $('.edit-currency-btn').click(function () {
        var currencyId = $(this).data('currency-id');

        // Fetch currency data by ID and populate the form
        getCurrencyDataById(currencyId);
    });

    // Handle click event on the "Update" button in the edit modal
    $('#updateButton').click(function () {
        // Validate the form
        var form = $('#editCurrencyForm')[0];
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            // Scroll to the first invalid field
            $('html, body').animate({
                scrollTop: $('.form-control:invalid').first().offset().top - 50
            }, 500);
            return;
        }

        // Get the form data
        var currencyId = $('#editCurrencyId').val();
        var newName = $('#editName').val();
        var newCode = $('#editCode').val().toUpperCase(); // Convert code to uppercase

        // Fetch currency data from the provided URL
        $.ajax({
            url: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json',
            type: 'GET',
            success: function (data) {
                // Check if the entered name matches the code
                var matchingCode = Object.keys(data).find(key => data[key] === newName).toUpperCase();

                if (!matchingCode || matchingCode !== newCode) {
                    // Display error message for mismatched name and code
                    $('#loadingButton').addClass('d-none');
                    $('#editName').addClass('is-invalid');
                    $('#editCode').addClass('is-invalid');
                    $('.invalid-feedback').html('The entered name does not match the selected code.');
                    return;
                }

                // Continue with form submission if the name matches the code
                // Create a data object with the form data and CSRF token
                var formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: newName,
                    code: newCode,
                };

                // Submit the form data to the specified URL for updating
                $.ajax({
                    url: '/currencies/' + currencyId,
                    type: 'PUT', // Use the PUT method for updating
                    data: formData,
                    success: function (response) {
                        form.reset();
                        form.classList.remove('was-validated');
                        // hide the modal 
                        $('#editCurrencyModal').modal('hide');

                        // Update the table row with the new data
                        var editedRow = $('tr[data-currency-id="' + currencyId + '"]');
                        editedRow.find('td:eq(0)').text(newName);
                        editedRow.find('td:eq(1)').text(newCode);

                        // Display success message in the success alert container
                        $('.success-message').show().html('Currency updated successfully');

                        // hide the success message after  3000 milliseconds
                        setTimeout(function () {
                            $('.success.alert').hide();
                            // Reload the page 
                            location.reload();
                        }, 3000);

                        
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response here
                        console.log(xhr.responseText);

                        // Hide any existing alert
                        $('.edit.alert').hide();

                        // Handle validation errors
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;

                            // Clear previous error messages
                            $('.invalid-feedback').empty();

                            // Display error messages for each field
                            $.each(errors, function (field, messages) {
                                var inputField = $('#' + field);
                                
                                    $('.edit.alert').show().html(messages.join('<br>'));
                          
                                    inputField.addClass('is-invalid');
                                    var errorContainer = inputField.next('.invalid-feedback');
                                    errorContainer.html(messages.join('<br>'));
                                
                            });

                        }
                    }

                });
            },
            error: function (xhr, status, error) {
                // Handle error fetching currency data
                console.log(xhr.responseText);
            }
        });
    });
});