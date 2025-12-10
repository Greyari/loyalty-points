<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, SkipsEmptyRows
{
    private $headerFound = false;
    private $headerColumns = [];
    private $currentRow = 0;
    private $importedCount = 0;
    private $skippedCount = 0;

    public function model(array $row)
    {
        $this->currentRow++;

        // Cari header dulu
        if (!$this->headerFound) {
            if ($this->detectHeader($row)) {
                $this->headerFound = true;
                $this->headerColumns = $this->mapHeaderColumns($row);
                Log::info("✓ Header found at row {$this->currentRow}", ['columns' => $this->headerColumns]);
                return null;
            }
            // Skip baris sebelum header
            return null;
        }

        // Skip footer/invalid rows
        if ($this->isFooterOrInvalidRow($row)) {
            $this->skippedCount++;
            return null;
        }

        // Extract data berdasarkan header columns
        $data = $this->extractData($row);

        if (!$data) {
            $this->skippedCount++;
            return null;
        }

        // Log data yang akan diimport
        Log::info("→ Row {$this->currentRow}: Importing", $data);

        try {
            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'quantity' => $data['quantity'],
                    'price' => $data['price'] ?? 0,
                    'points_per_unit' => $data['points_per_unit'] ?? 0
                ]
            );

            $this->importedCount++;
            Log::info("✓ Success: {$product->name} (SKU: {$product->sku})");

            return $product;

        } catch (\Exception $e) {
            Log::error("✗ Failed row {$this->currentRow}: " . $e->getMessage());
            $this->skippedCount++;
            return null;
        }
    }

    /**
     * Deteksi apakah baris ini adalah header
     */
    private function detectHeader(array $row): bool
    {
        $headerKeywords = [
            'description', 'desc', 'product', 'item', 'name',  // untuk kolom nama
            'sku', 'code', 'item no', 'product code',          // untuk kolom SKU
            'qty', 'quantity', 'stock', 'amount'                // untuk kolom quantity
        ];

        $matchCount = 0;
        foreach ($row as $cell) {
            $cellValue = strtolower(trim($cell));

            foreach ($headerKeywords as $keyword) {
                if (stripos($cellValue, $keyword) !== false && strlen($cellValue) < 50) {
                    $matchCount++;
                    break;
                }
            }
        }

        // Jika ada minimal 2 keyword yang match, anggap sebagai header
        return $matchCount >= 2;
    }

    /**
     * Map kolom mana yang berisi data apa
     */
    private function mapHeaderColumns(array $row): array
    {
        $mapping = [
            'name' => null,
            'sku' => null,
            'quantity' => null,
            'price' => null
        ];

        foreach ($row as $index => $cell) {
            $cellValue = strtolower(trim($cell));

            // Deteksi kolom Name/Description
            if (preg_match('/(description|desc|product|item|name)/i', $cellValue) && !$mapping['name']) {
                $mapping['name'] = $index;
            }

            // Deteksi kolom SKU/Code
            if (preg_match('/(sku|code|item.*no|product.*code)/i', $cellValue) && !$mapping['sku']) {
                $mapping['sku'] = $index;
            }

            // Deteksi kolom Quantity
            if (preg_match('/(qty|quantity|stock|amount)/i', $cellValue) && !$mapping['quantity']) {
                $mapping['quantity'] = $index;
            }

            // Deteksi kolom Price
            if (preg_match('/(price|harga|cost)/i', $cellValue) && !$mapping['price']) {
                $mapping['price'] = $index;
            }
        }

        return $mapping;
    }

    /**
     * Extract data dari row berdasarkan mapping
     */
    private function extractData(array $row): ?array
    {
        // Jika tidak ada mapping, coba deteksi otomatis dari data
        if (empty(array_filter($this->headerColumns))) {
            return $this->extractDataAutomatic($row);
        }

        $name = isset($this->headerColumns['name']) ? trim($row[$this->headerColumns['name']] ?? '') : '';
        $sku = isset($this->headerColumns['sku']) ? trim($row[$this->headerColumns['sku']] ?? '') : '';
        $quantity = isset($this->headerColumns['quantity']) ? trim($row[$this->headerColumns['quantity']] ?? '0') : '0';
        $price = isset($this->headerColumns['price']) ? trim($row[$this->headerColumns['price']] ?? '0') : '0';

        // Validasi data
        if (empty($name) || empty($sku)) {
            Log::info("→ Row {$this->currentRow}: Skipped - empty name or SKU");
            return null;
        }

        // Skip jika ini header yang terdeteksi sebagai data
        if ($this->looksLikeHeader($name, $sku)) {
            Log::info("→ Row {$this->currentRow}: Skipped - looks like header");
            return null;
        }

        // Validasi SKU minimal 3 karakter
        if (strlen($sku) < 3) {
            Log::info("→ Row {$this->currentRow}: Skipped - SKU too short");
            return null;
        }

        // Clean quantity dan price
        $quantity = (float)str_replace([',', ' '], '', $quantity);
        $price = (float)str_replace([',', ' ', 'Rp'], '', $price);

        return [
            'name' => $name,
            'sku' => $sku,
            'quantity' => (int)$quantity,
            'price' => $price,
            'points_per_unit' => 0
        ];
    }

    /**
     * Extract data otomatis jika tidak ada header mapping
     */
    private function extractDataAutomatic(array $row): ?array
    {
        // Cari kolom yang tidak kosong
        $nonEmptyCols = [];
        foreach ($row as $index => $value) {
            if (!empty(trim($value))) {
                $nonEmptyCols[$index] = trim($value);
            }
        }

        // Harus minimal ada 3 kolom (name, sku, quantity)
        if (count($nonEmptyCols) < 3) {
            return null;
        }

        $values = array_values($nonEmptyCols);

        return [
            'name' => $values[0] ?? '',
            'sku' => $values[1] ?? '',
            'quantity' => (int)(float)str_replace(',', '', $values[2] ?? '0'),
            'price' => 0,
            'points_per_unit' => 0
        ];
    }

    /**
     * Check apakah baris ini seperti header
     */
    private function looksLikeHeader(string $name, string $sku): bool
    {
        $headerPatterns = [
            'item description', 'product description', 'description',
            'item no', 'product code', 'sku', 'code',
            'quantity', 'qty', 'stock'
        ];

        $nameLower = strtolower($name);
        $skuLower = strtolower($sku);

        foreach ($headerPatterns as $pattern) {
            if (strcasecmp($name, $pattern) === 0 || strcasecmp($sku, $pattern) === 0) {
                return true;
            }
            if (stripos($nameLower, $pattern) !== false || stripos($skuLower, $pattern) !== false) {
                // Jika text pendek dan mengandung keyword header
                if (strlen($name) < 30 && strlen($sku) < 30) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check apakah ini footer atau baris invalid
     */
    private function isFooterOrInvalidRow(array $row): bool
    {
        $footerKeywords = [
            'accurate', 'printed', 'page', 'total', 'subtotal',
            'grand total', 'report', 'end of', 'summary'
        ];

        foreach ($row as $cell) {
            $cellValue = strtolower(trim($cell));

            foreach ($footerKeywords as $keyword) {
                if (stripos($cellValue, $keyword) !== false) {
                    Log::info("→ Row {$this->currentRow}: Skipped - footer detected");
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get import statistics
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getStats(): array
    {
        return [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount,
            'total' => $this->currentRow
        ];
    }
}
