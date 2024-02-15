$(document).ready(function () {
let isEditMode = false;

// Event listener when the name input changes
$('#name, #editName').on('input', async function () {
    const searchTerm = $(this).val().trim().toLowerCase();
    const listId = isEditMode ? 'editCurrencyNameList' : 'currencyList';

    // Clear previous options
    $(`#${listId}`).html('');

    if (searchTerm.length >= 1) {
        // Fetch currency data from the provided URL
        const response = await fetch('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json');
        const data = await response.json();

        // Limit results to 3 based on currency input
        const filteredCurrencies = Object.entries(data)
            .filter(([code, name]) => name.toLowerCase().includes(searchTerm))
            .slice(0, 3);

        // Populate the datalist with filtered options
        for (const [code, name] of filteredCurrencies) {
            const option = document.createElement('option');
            option.value = name;
            option.dataset.code = code;
            $(`#${listId}`).append(option);
        }
    }
});

// Event listener when the code input changes
$('#code, #editCode').on('input', async function () {
    const searchTerm = $(this).val().trim().toLowerCase();
    const listId = isEditMode ? 'editCurrencyCodeList' : 'CodeList';

    // Clear previous options
    $(`#${listId}`).html('');

    if (searchTerm.length >= 1) {
        // Fetch currency data from the provided URL
        const response = await fetch('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json');
        const data = await response.json();

        // Limit results to 3 based on currency input
        const filteredCurrencies = Object.entries(data)
            .filter(([code, name]) => code.toLowerCase().includes(searchTerm))
            .slice(0, 3);

        // Populate the datalist with filtered options
        for (const [code, name] of filteredCurrencies) {
            const option = document.createElement('option');
            option.value = code;
            option.dataset.name = name;
            $(`#${listId}`).append(option);
        }
    }
});

// Event listener to dynamically detect whether it's an edit or add operation
$('#name, #editName, #code, #editCode').on('focus', function () {
    isEditMode = $(this).attr('id').startsWith('edit');
});

});