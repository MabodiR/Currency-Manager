$(document).ready(function() {
    $('.add').hide();
    // Show the modal when the "Add New currency" button is clicked
    $('#addCurrencyModalBtn').click(function() {
        $('#addcurrencyModal').modal('show');
    });

      // Show the modal when the "Add New currency" button is clicked
      $('#addCurrencyModalBtn').click(function () {
        $('#addCurrencyModal').modal('show');
      });

 $('#saveButton').click(function () {
    // Disable the save button and show the loading button
    $('#loadingButton').removeClass('d-none');

    // Validate the form
    var form = $('#addCurrencyForm')[0];
    if (!form.checkValidity()) {
        form.classList.add('was-validated');

        // Scroll to the first invalid field
        $('html, body').animate({
            scrollTop: $('.form-control:invalid').first().offset().top - 50
        }, 500);

        // Enable the save button and hide the loading button
        $('#loadingButton').addClass('d-none');
        return;
    }

    // Get the form data
    var name = $('#name').val();
    var code = $('#code').val();

    // Fetch currency data from the provided URL
    $.ajax({
        url: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json',
        type: 'GET',
        success: function (data) {
            // Check if the entered name matches the code
            var matchingCode = Object.keys(data).find(key => data[key] === name);

            if (!matchingCode || matchingCode !== code) {
                // Display error message for mismatched name and code
                $('#loadingButton').addClass('d-none');
                $('#name').addClass('is-invalid');
                $('#code').addClass('is-invalid');
                $('.invalid-feedback').html('The entered name does not match the selected code.');
                return;
            }

            // Continue with form submission if the name matches the code
            // Create a data object with the form data and CSRF token
            var formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: name,
                code: code.toUpperCase(), // Convert code to uppercase
            };

            // Submit the form data to the specified URL
            $.ajax({
                url: '/currencies',
                type: 'POST',
                data: formData,
                success: function (response) {
                    form.reset();
                    form.classList.remove('was-validated');
                    $('.invalid-feedback').empty();
                    $('#addcurrencyModal').modal('hide');
                    // Display success message in the success alert container
                    $('.success-message').show().html('Currency added successfully');

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
                            $('.add.alert').hide();

                            // Handle validation errors
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;

                                // Clear previous error messages
                                $('.invalid-feedback').empty();

                                // Display error messages for each field
                                $.each(errors, function (field, messages) {
                                    var inputField = $('#' + field);

                                    if (inputField.length === 0) {
                                        // If the field is not found, display the error in the alert container
                                        $('.add.alert').show().html(messages.join('<br>'));
                                    } else {
                                        inputField.addClass('is-invalid');
                                        var errorContainer = inputField.next('.invalid-feedback');
                                        errorContainer.html(messages.join('<br>'));
                                    }
                                });
                            }
                        }
            });
        },
        error: function (xhr, status, error) {
            // Handle error fetching currency data
            console.log(xhr.responseText);
            // Enable the save button and hide the loading button
            $('#loadingButton').addClass('d-none');
        }
    });
});

});