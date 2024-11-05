<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the CSV file in the csv folder
        $csvFile = database_path('csv/categories.csv');

        // Load the CSV file
        $reader = Reader::createFromPath($csvFile, 'r');
        $reader->setHeaderOffset(0); // Set the header offset to 0 to use the first row as the header

        // Insert data into the event_categories table
        foreach ($reader as $record) {
            DB::table('categories')->insert([
                'name' => $record['name'],
            ]);
        }
    }
}
