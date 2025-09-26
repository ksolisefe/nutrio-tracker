<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Food;
use Illuminate\Support\Facades\DB;

class ImportUsdaBrandedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-usda-branded-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import USDA Branded Foods data from JSON file (optimized)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '2G'); // Increase memory limit
        
        $this->info('Importing USDA Branded Foods data...');
        $filePath = database_path('seeders/data/usda_branded_foods.json');
        $this->info("Loading file: {$filePath}");
        
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Could not open file: {$filePath}");
            return;
        }

        // Skip the opening {"BrandedFoods": [
        fgets($handle);

        $batch = [];
        $batchSize = 1000;
        $count = 0;
        
        // Use transaction for better performance
        DB::transaction(function() use ($handle, &$batch, $batchSize, &$count) {
            while (($line = fgets($handle)) !== false) {
                // Skip empty lines
                $line = trim($line);
                if (empty($line) || $line === '[' || $line === ']' || $line === '}') {
                    continue;
                }
                
                // Remove trailing comma if present
                $line = rtrim($line, ',');
                
                // Try to parse this line as JSON
                $item = json_decode($line, true);
                if (!$item) {
                    continue;
                }
                
                $fdcId = $item['fdcId'] ?? null;
                $name = $item['description'] ?? '';
                
                // Branded-specific fields
                $brandOwner = $item['brandOwner'] ?? '';
                $brandedFoodCategory = $item['brandedFoodCategory'] ?? '';
                $gtinUpc = $item['gtinUpc'] ?? '';
                $servingSize = $item['servingSize'] ?? 0;
                $servingSizeUnit = $item['servingSizeUnit'] ?? '';
                
                // Initialize nutrient values
                $calories = 0;
                $protein = 0;
                $carbohydrates = 0;
                $fat = 0;
                $fiber = 0;
                $sugars = 0;
                $sodium = 0;

                // Extract nutrients from foodNutrients array
                if (!empty($item['foodNutrients']) && is_array($item['foodNutrients'])) {
                    foreach ($item['foodNutrients'] as $foodNutrient) {
                        if (!isset($foodNutrient['nutrient']) || !is_array($foodNutrient['nutrient'])) {
                            continue;
                        }
                        $nutrientName = $foodNutrient['nutrient']['name'] ?? '';
                        $unitName = $foodNutrient['nutrient']['unitName'] ?? '';
                        $amount = $foodNutrient['amount'] ?? 0;

                        // Map nutrients to variables
                        switch ($nutrientName) {
                            case 'Energy':
                                if ($unitName === 'kcal') {
                                    $calories = $amount;
                                }
                                break;
                            case 'Protein':
                                $protein = $amount;
                                break;
                            case 'Carbohydrate, by difference':
                                $carbohydrates = $amount;
                                break;
                            case 'Total lipid (fat)':
                                $fat = $amount;
                                break;
                            case 'Fiber, total dietary':
                                $fiber = $amount;
                                break;
                            case 'Total Sugars':
                                $sugars = $amount;
                                break;
                            case 'Sodium, Na':
                                $sodium = $amount;
                                break;
                        }
                    }
                }

                // Also extract simplified nutrients from labelNutrients if available
                if (!empty($item['labelNutrients']) && is_array($item['labelNutrients'])) {
                    $labelNutrients = $item['labelNutrients'];
                    
                    // Use label nutrients as fallback if detailed nutrients not found
                    if ($calories == 0 && isset($labelNutrients['calories']['value'])) {
                        $calories = $labelNutrients['calories']['value'];
                    }
                    if ($protein == 0 && isset($labelNutrients['protein']['value'])) {
                        $protein = $labelNutrients['protein']['value'];
                    }
                    if ($carbohydrates == 0 && isset($labelNutrients['carbohydrates']['value'])) {
                        $carbohydrates = $labelNutrients['carbohydrates']['value'];
                    }
                    if ($fat == 0 && isset($labelNutrients['fat']['value'])) {
                        $fat = $labelNutrients['fat']['value'];
                    }
                    if ($fiber == 0 && isset($labelNutrients['fiber']['value'])) {
                        $fiber = $labelNutrients['fiber']['value'];
                    }
                    if ($sugars == 0 && isset($labelNutrients['sugars']['value'])) {
                        $sugars = $labelNutrients['sugars']['value'];
                    }
                    if ($sodium == 0 && isset($labelNutrients['sodium']['value'])) {
                        $sodium = $labelNutrients['sodium']['value'];
                    }
                }

                // Add to batch
                $batch[] = [
                    'fdc_id' => $fdcId,
                    'name' => $name,
                    'food_type' => 'branded',
                    'category' => $brandedFoodCategory,
                    'calories' => $calories,
                    'protein' => $protein,
                    'carbohydrates' => $carbohydrates,
                    'fat' => $fat,
                    'brand_owner' => $brandOwner,
                    'gtin_upc' => $gtinUpc,
                    'serving_size' => $servingSize,
                    'serving_size_unit' => $servingSizeUnit,
                    'fibre' => $fiber,
                    'sodium' => $sodium,
                    'sugars' => $sugars,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $count++;

                // Insert batch when it reaches batchSize
                if (count($batch) >= $batchSize) {
                    Food::insert($batch);
                    $this->info("Inserted {$count} branded foods...");
                    $batch = []; // Reset batch
                }
            }

            // Insert remaining records
            if (!empty($batch)) {
                Food::insert($batch);
            }
        });
        
        fclose($handle);
        $this->info("✅ Successfully imported {$count} branded foods!");
    }
}