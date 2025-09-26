<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Food;
use Illuminate\Support\Facades\DB;

class ImportUsdaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-usda-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import USDA Foundation Foods data to database (optimized)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Importing USDA Foundation Foods data...');
        $filePath = database_path('seeders/data/usda_foundation_foods.json');
        $this->info("Loading file: {$filePath}");
        
        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        $total = count($data['FoundationFoods']);
        $this->info("Processing {$total} foundation foods...");

        $batch = [];
        $batchSize = 1000;
        $count = 0;

        // Use transaction for better performance
        DB::transaction(function() use ($data, &$batch, $batchSize, &$count) {
            foreach ($data['FoundationFoods'] as $item) {
                $fdcId = $item['fdcId'] ?? null;
                $name = $item['description'] ?? '';
                $category = isset($item['foodCategory']['description']) ? $item['foodCategory']['description'] : '';
                
                // Initialize nutrients
                $calories = 0;
                $protein = 0;
                $carbohydrates = 0;
                $fat = 0;

                // Extract nutrients
                if (!empty($item['foodNutrients']) && is_array($item['foodNutrients'])) {
                    foreach ($item['foodNutrients'] as $foodNutrient) {
                        if (!isset($foodNutrient['nutrient'])) continue;
                        
                        $nutrientName = $foodNutrient['nutrient']['name'] ?? '';
                        $unitName = $foodNutrient['nutrient']['unitName'] ?? '';
                        $amount = $foodNutrient['amount'] ?? 0;

                        if ($nutrientName === 'Energy' && $unitName === 'kcal') {
                            $calories = $amount;
                        } elseif ($nutrientName === 'Protein') {
                            $protein = $amount;
                        } elseif ($nutrientName === 'Carbohydrate, by difference') {
                            $carbohydrates = $amount;
                        } elseif ($nutrientName === 'Total lipid (fat)') {
                            $fat = $amount;
                        }
                    }
                }

                // Add to batch
                $batch[] = [
                    'fdc_id' => $fdcId,
                    'name' => $name,
                    'food_type' => 'foundation',
                    'category' => $category,
                    'calories' => $calories,
                    'protein' => $protein,
                    'carbohydrates' => $carbohydrates,
                    'fat' => $fat,
                    'brand_owner' => null,
                    'gtin_upc' => null,
                    'serving_size' => null,
                    'serving_size_unit' => null,
                    'fibre' => null,
                    'sodium' => null,
                    'sugars' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $count++;

                // Insert batch when it reaches batchSize
                if (count($batch) >= $batchSize) {
                    Food::insert($batch);
                    $this->info("Inserted {$count} foundation foods...");
                    $batch = []; // Reset batch
                }
            }

            // Insert remaining records
            if (!empty($batch)) {
                Food::insert($batch);
            }
        });

        $this->info("✅ Successfully imported {$count} foundation foods!");
    }
}