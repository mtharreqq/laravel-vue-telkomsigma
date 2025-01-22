<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persons;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PersonsController extends Controller
{
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
                            'gender' => $person['gender'],
                            'dob' => substr($person['dob']['date'], 0, 10),
                            'country' => "{$person['location']['country']}",
                        ];
                    }
                );

                // Insert each person into the database
                foreach ($filteredData as $data) {
                    Persons::create($data);
                }

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

    public function dashboard(Request $request)
    {
        $today = date('Y-m-d');
        $fromDate = $request->input('startDate', '1800-01-01');
        $toDate = $request->input('endDate', $today);

        $persons = Persons::whereBetween('dob', [$fromDate, $toDate])
            ->orderBy('dob', 'desc')
            ->get();

        $males = Persons::where('gender', 'male')
            ->whereBetween('dob', [$fromDate, $toDate])
            ->latest('dob')
            ->get();

        $females = Persons::where('gender', 'female')
            ->whereBetween('dob', [$fromDate, $toDate])
            ->latest('dob')
            ->get();

        return Inertia::render(
            'Dashboard/Index',
            compact('persons', 'males', 'females', 'fromDate', 'toDate')
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persons = Persons::latest()->get();
        return Inertia::render('Persons/Index', ['persons' => $persons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Persons/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $person = new Persons($request->all());
        $person->save();

        return redirect()->route('persons.index');
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
    public function edit(Persons $person)
    {
        return Inertia::render('Persons/Edit', ['person' => $person]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persons $person)
    {
        $person->update($request->all());
        return redirect()->route('persons.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persons $person)
    {
        $person->delete();
        return redirect()->back();
    }
}
