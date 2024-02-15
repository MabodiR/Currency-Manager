<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use GuzzleHttp\Client;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the currencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch exchange rates from an API (e.g., https://open.er-api.com/v6/latest)
        $apiEndpoint = 'https://open.er-api.com/v6/latest';
        $client = new Client();
        $response = $client->get($apiEndpoint);
        $data = json_decode($response->getBody(), true);

        // Retrieve all currencies from the database
        $currencies = Currency::all();

        // Pass currencies data and exchange rates to the view for listing
        return view('currencies.index', compact('currencies', 'data'));
    }

    /**
     * Show the form for creating a new currency.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Render the form for adding a new currency
        return view('currencies.create');
    }

    /**
     * Store a newly created currency in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:currencies',
        ]);

        // Create a new currency instance and save it to the database
        Currency::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);

        // Redirect back to the index page with a success message
        return redirect()->route('currencies.index')->with('success', 'Currency added successfully!');
    }

    /**
     * Show the form for editing the specified currency.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        // Render the form for editing the specified currency
        return view('currencies.edit', compact('currency'));
    }

    public function show($id)
    {
        // Assuming you have a Currency model
        $currency = Currency::findOrFail($id);

        // You can customize the response based on your needs
        return response()->json(['currency' => $currency]);
    }

    /**
     * Update the specified currency in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:6|unique:currencies,code,' . $currency->id,
        ]);

        // Update the currency with the new data
        $currency->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);

         // Return a JSON response indicating success
         return response()->json([
            'success' => true,
            'message' => 'Currency updated successfully'
        ]);
    }

    /**
     * Remove the specified currency from the database.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        // Delete the specified currency
        $currency->delete();

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Currency deleted successfully'
        ]);
    }
}
