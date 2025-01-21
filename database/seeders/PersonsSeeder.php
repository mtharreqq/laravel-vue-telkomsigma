<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persons;
use Illuminate\Support\Facades\Http;

class PersonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://randomuser.me/api/?results=100');
        // Check if the request was successful
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
        } else {
            $this->command->error(
                'Failed to fetch data from the Random User API.'
            );
        }
    }
}
