<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonsRequest;
use App\Http\Requests\UpdatePersonsRequest;
use App\Models\Persons;
use Illuminate\Support\Facades\Http;

class PersonsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function addPersons()
    {
        try {
            // Fetch data from API
            $response = Http::get('https://randomuser.me/api/?results=5');

            if ($response->successful()) {
                $filteredData = collect($response->json()['results'])->map(
                    function ($person) {
                        return [
                            'name' => "{$person['name']['first']} {$person['name']['last']}",
                            'email' => $person['email'],
                            'gender' => $person['gender'],
                            'phone' => $person['phone'],
                            'dob' => "{$person['dob']['date']}",
                            'country' => "{$person['location']['country']}",
                        ];
                    }
                );

                // Insert each person into the database
                foreach ($filteredData as $data) {
                    Persons::create($data);
                }

                // return response()->json(
                //     ['message' => 'Persons added successfully!'],
                //     200
                // );
                return redirect()
                    ->back()
                    ->with('success', 'Persons added successfully!');
            }

            return response()->json(
                ['error' => 'Failed to fetch persons'],
                500
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete5Persons()
    {
        // Retrieve 25 persons (you can customize the condition)
        $persons = Persons::take(5)->get();

        if ($persons->isEmpty()) {
            return response()->json(
                [
                    'message' => 'No persons found to delete.',
                ],
                404
            );
        }

        // Delete the persons
        $personIds = $persons->pluck('id');
        Persons::destroy($personIds);

        return redirect()
            ->back()
            ->with('success', '25 Persons removed successfully!');
    }

    public function deleteAllPersons()
    {
        try {
            Persons::truncate();
            return redirect()
                ->back()
                ->with('success', 'Persons removed successfully!');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Persons $persons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persons $persons)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonsRequest $request, Persons $persons)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persons $persons)
    {
        //
    }
}
