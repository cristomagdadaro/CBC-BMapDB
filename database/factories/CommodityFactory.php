<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commodity>
 */
class CommodityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $locations = $this->readFromCsv();
        $locations = config('ph_geo_cities');

        $randomLocation = $this->faker->randomElement($locations);
        $address = $this->faker->address;
        return [
            'name' => $this->faker->name,
            'breeder_id' => $this->faker->randomElement(\App\Models\Breeder::pluck('id')->toArray()),
            'scientific_name' => $this->faker->name,
            'variety' => $this->faker->name,
            'accession' => $this->faker->name,
            'germplasm' => $this->faker->name,
            'population' => $this->faker->randomFloat(),
            'maturity_period' => $this->faker->name,
            'yield' => $this->faker->randomFloat(),
            'description' => $this->faker->text,
            'image' => $this->faker->imageUrl(),
            'latitude' => $randomLocation['latitude'],
            'longitude' => $randomLocation['longitude'],
            'address' => $address,
            'city' => $randomLocation['name'],
            'province' => $randomLocation['province'],
            'country' => 'Philippines',
            'postal_code' => $this->faker->postcode,
            'formatted_address' => $address . ', ' . $randomLocation['name'] . ', ' . $randomLocation['province'] . ', Philippines',
            'place_id' => 'PH',
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    function readFromCsv(){
        // Define the path to your CSV file
        $csvFilePath = "config/ph_geo_cities.csv";

        // Call the function and get the formatted data
        $formattedData = $this->readCsvAndFormat($csvFilePath);

        // Print the formatted data
        if ($formattedData !== false) {
            // Define the output file path
            $outputFilePath = "config/ph_geo_cities.php";

            // Save the formatted data to the output file
            $this->saveFormattedDataToFile($formattedData, $outputFilePath);
        } else {
            echo "Failed to read the CSV file.";
        }
        return $formattedData;
    }

    // Function to read CSV file and return formatted data
    function readCsvAndFormat($csvFilePath) {
        // Open the CSV file
        if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
            // Skip the header row
            fgetcsv($handle);

            // Initialize an array to hold the formatted data
            $formattedData = [];

            // Loop through each row in the CSV file
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Map CSV columns to variables
                $name = $data[0];
                $latitude = (float)$data[1];
                $longitude = (float)$data[2];

                // Determine province and region based on city name (customize this part as needed)
                $province = $this->determineProvince($name);
                $region = $this->determineRegion($name);

                // Add formatted data to the array
                $formattedData[] = [
                    'name' => $name,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'province' => $province,
                    'region' => $region,
                ];
            }

            // Close the CSV file
            fclose($handle);

            return $formattedData;
        } else {
            return false;
        }
    }

    // Custom function to determine province based on city name (customize this part as needed)
    function determineProvince($cityName): string
    {
        // This is a simplified example. You might need a more comprehensive mapping.
        $provinceMapping = [
            'Manila' => 'Metro Manila',
            'Quezon City' => 'Metro Manila',
            'Zamboanga City' => 'Zamboanga del Sur',
            'Davao' => 'Davao del Sur',
            'Caloocan City' => 'Metro Manila',
            'Canagatan' => 'Apayao',
            'Taguig City' => 'Metro Manila',
            'Pasig City' => 'Metro Manila',
            'Valenzuela' => 'Metro Manila',
            'City of Parañaque' => 'Metro Manila',
            'Bacoor' => 'Cavite',
            'Tondo' => 'Metro Manila',
            'Las Piñas City' => 'Metro Manila',
            'Pasay City' => 'Metro Manila',
            'Mandaluyong City' => 'Metro Manila',
            'Sampaloc' => 'Metro Manila',
            'Malabon' => 'Metro Manila',
            'San Pedro' => 'Laguna',
            'Navotas' => 'Metro Manila',
            'Santa Ana' => 'Metro Manila',
            'General Mariano Alvarez' => 'Cavite',
            'Payatas' => 'Metro Manila',
            'San Andres' => 'Metro Manila',
            'Santa Cruz' => 'Metro Manila',
            'San Juan' => 'Metro Manila',
            'Poblacion' => 'Metro Manila',
            'Santamesa' => 'Metro Manila',
            'Rosario' => 'Cavite',
            'Bagong Silangan' => 'Metro Manila',
            'Putatan' => 'Metro Manila',
            'Malate' => 'Metro Manila',
            'Western Bicutan' => 'Metro Manila',
            'Pandacan' => 'Metro Manila',
            'Banco Filipino International Village' => 'Metro Manila',
            'Paco' => 'Metro Manila',
            'San Isidro' => 'Metro Manila',
            'San Antonio' => 'Metro Manila',
            'Pateros' => 'Metro Manila',
            'Sucat' => 'Metro Manila',
            'Lower Bicutan' => 'Metro Manila',
            'Tatalon' => 'Metro Manila',
            'Don Bosco' => 'Metro Manila',
            'Bignay' => 'Metro Manila',
            'Bagumbayan' => 'Metro Manila',
            'Upper Bicutan' => 'Metro Manila',
            'Central Signal Village' => 'Metro Manila',
            'Marikina Heights' => 'Metro Manila',
            'Bayanan' => 'Metro Manila',
            'Karuhatan' => 'Metro Manila',
            'Bel-Air' => 'Metro Manila',
            'Pansol' => 'Metro Manila',
            'Baclaran' => 'Metro Manila',
            'Quiapo' => 'Metro Manila',
            'West Rembo' => 'Metro Manila',
            'Bagong Pag-Asa' => 'Metro Manila',
            'Santo Niño' => 'Metro Manila',
            'Pinyahan' => 'Metro Manila'
        ];

        return $provinceMapping[$cityName] ?? 'Unknown Province';
    }

    // Custom function to determine region based on city name (customize this part as needed)
    function determineRegion($cityName): string
    {
        // This is a simplified example. You might need a more comprehensive mapping.
        $regionMapping = [
            'Manila' => 'NCR',
            'Quezon City' => 'NCR',
            'Zamboanga City' => 'IX (Zamboanga Peninsula)',
            'Davao' => 'XI (Davao Region)',
            'Caloocan City' => 'NCR',
            'Canagatan' => 'CAR',
            'Taguig City' => 'NCR',
            'Pasig City' => 'NCR',
            'Valenzuela' => 'NCR',
            'City of Parañaque' => 'NCR',
            'Bacoor' => 'IV-A (CALABARZON)',
            'Tondo' => 'NCR',
            'Las Piñas City' => 'NCR',
            'Pasay City' => 'NCR',
            'Mandaluyong City' => 'NCR',
            'Sampaloc' => 'NCR',
            'Malabon' => 'NCR',
            'San Pedro' => 'IV-A (CALABARZON)',
            'Navotas' => 'NCR',
            'Santa Ana' => 'NCR',
            'General Mariano Alvarez' => 'IV-A (CALABARZON)',
            'Payatas' => 'NCR',
            'San Andres' => 'NCR',
            'Santa Cruz' => 'NCR',
            'San Juan' => 'NCR',
            'Poblacion' => 'NCR',
            'Santamesa' => 'NCR',
            'Rosario' => 'IV-A (CALABARZON)',
            'Bagong Silangan' => 'NCR',
            'Putatan' => 'NCR',
            'Malate' => 'NCR',
            'Western Bicutan' => 'NCR',
            'Pandacan' => 'NCR',
            'Banco Filipino International Village' => 'NCR',
            'Paco' => 'NCR',
            'San Isidro' => 'NCR',
            'San Antonio' => 'NCR',
            'Pateros' => 'NCR',
            'Sucat' => 'NCR',
            'Lower Bicutan' => 'NCR',
            'Tatalon' => 'NCR',
            'Don Bosco' => 'NCR',
            'Bignay' => 'NCR',
            'Bagumbayan' => 'NCR',
            'Upper Bicutan' => 'NCR',
            'Central Signal Village' => 'NCR',
            'Marikina Heights' => 'NCR',
            'Bayanan' => 'NCR',
            'Karuhatan' => 'NCR',
            'Bel-Air' => 'NCR',
            'Pansol' => 'NCR',
            'Baclaran' => 'NCR',
            'Quiapo' => 'NCR',
            'West Rembo' => 'NCR',
            'Bagong Pag-Asa' => 'NCR',
            'Santo Niño' => 'NCR',
            'Pinyahan' => 'NCR'
        ];

        return $regionMapping[$cityName] ?? 'Unknown Region';
    }

    private function saveFormattedDataToFile($formattedData, $filePath)
    {
        $output = "<?php\n\nreturn " . var_export($formattedData, true) . ";\n";
        file_put_contents($filePath, $output);
    }
}
