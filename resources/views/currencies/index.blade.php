<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Currency Manager Listing</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Stripe the table rows */
        table.table-striped tbody tr:nth-child(odd) {
            background-color: #ccc;
        }
         /* Set font to Helvetica */
        body {
            font-family: "Helvetica", Arial, sans-serif;
        }
        .bg-red {
            background-color: #a60000;
            padding: 10px;
            border-radius:4px;
            }

        .fa{
            padding: 5px;
            cursor: pointer;
        }

        .red{ color:red;}

        .blue{ color:blue;}

    </style>

    <!-- Include Select2 CSS and JS files -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script> -->
     <!-- Include Toastr CSS from CDN -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>
<body>

<!-- Slider Section -->
<div id="slider" class="carousel slide" data-ride="carousel">
    <!-- Add your slider content here -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/assets/img/bg/1.jpeg" alt="Slider Image 1" class="d-block w-100" style="height: 450px; object-fit: cover;">
            <div class="carousel-caption d-flex justify-content-center align-items-center h-100">
                <h1 class="bg-red">Currency Manager</h1>
            </div>
        </div>
    </div>
</div>

<!-- Currency Table -->
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#addCurrencyModal">Add New Currency</button>
    </div>
    <div class="table-responsive"> <!-- Add the table-responsive class here -->
        <table id="currencyTable" class="table table-striped">
        <div class="alert success-message alert-success"  role="alert"></div>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Exchange Rate to ZAR</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($currencies as $currency)
                    <tr data-currency-id="{{ $currency->id }}">
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->code }}</td>
                        <td>
                            
                        </td>
                        <td>
                            <i  class="fa fa-edit blue edit-currency-btn" data-toggle="modal" data-target="#editCurrencyModal" data-currency-id="{{ $currency->id }}"></i>
                                |
                             <i class="fa fa-trash-o red delete-currency" data-currency-id="{{ $currency->id }}"></i>
              
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No currencies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit currency Modal -->
<div class="modal fade" id="editCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="editCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCurrencyModalLabel">Edit Currency</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <div class="modal-body">
            <div class="alert edit alert-danger" role="alert"></div>
                <form id="editCurrencyForm" method="POST" action="/currencies">
                    @csrf <!-- Laravel CSRF token -->
                    @method('PUT') <!-- Use the PUT method for updating -->

                    <input type="hidden" name="currency_id" id="editCurrencyId">

                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" name="name" id="editName" list="editCurrencyNameList" required>
                        <datalist id="editCurrencyNameList">
                            <!-- Options will be dynamically populated using JavaScript -->
                        </datalist>
                        <div class="invalid-feedback">
                           
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editCode">Code</label>
                        <input type="text" class="form-control" name="code" id="editCode" list="editCurrencyCodeList" required>
                        <datalist id="editCurrencyCodeList">
                            <!-- Options will be dynamically populated using JavaScript -->
                        </datalist>
                        <div class="invalid-feedback">
                           
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark" id="updateButton">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Add currency Modal -->
<div class="modal fade" id="addCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="addCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCurrencyModalLabel">Add New Currency</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="alert add alert-danger" role="alert"></div>
                <form id="addCurrencyForm" method="Post" action="/currencies">
                {{ csrf_field() }} <!-- Add Laravel CSRF token -->

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" list="currencyList" placeholder="Search for a currency name" required>
                            <datalist id="currencyList">
                                <!-- Options will be dynamically populated using JavaScript -->
                            </datalist>
                        <div class="invalid-feedback">
                            Please enter a currency name.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="code" list="codeList" placeholder="Search for a currency code" required>
                        <datalist id="codeList">
                            <!-- Options will be dynamically populated using JavaScript -->
                        </datalist>
                        <div class="invalid-feedback">
                           Currency code cant be empty/ doesnt match the currency name.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark d-none" id="loadingButton" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn " data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-dark" id="saveButton">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- HTML Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Please confirm that you would like to delete this currency?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark d-none" id="deleteLoadingButton" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" id="confirmDeleteBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

</body>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>

<!-- Include Custom JS -->
<script src="{{asset('assets/js/add.js')}}"></script>
<script src="{{asset('assets/js/delete.js')}}"></script>
<script src="{{asset('assets/js/filter.js')}}"></script>
<script src="{{asset('assets/js/edit.js')}}"></script>


<script>
$(document).ready(function() {

// Fetch the latest exchange rates from the API
$.ajax({
    url: 'https://open.er-api.com/v6/latest/ZAR',
    type: 'GET',
    success: function (exchangeData) {
        // Loop through each row in the table
        $('#currencyTable tbody tr').each(function () {
            // Get the currency code from the table row
            var currencyCode = $(this).find('td:eq(1)').text();

            // Find the corresponding exchange rate in the fetched data
            var exchangeRate = exchangeData.rates[currencyCode];

            // Update the "Exchange Rate to ZAR" cell in the table
            var exchangeRateCell = $(this).find('td:eq(2)');
            if (exchangeRate !== undefined) {
                  // Round the exchange rate to 3 decimal places
                  var roundedExchangeRate = (1 / exchangeRate).toFixed(3);

                // Display the exchange rate in the format "1 ZAR = X"
                exchangeRateCell.text(`1 ZAR = ${roundedExchangeRate} ${currencyCode}`);
            } else {
                exchangeRateCell.text('N/A');
            }
        });
    },
    error: function (xhr, status, error) {
        // Handle error fetching exchange rates
        console.log(xhr.responseText);
    }
});

});
</script>
</html>
