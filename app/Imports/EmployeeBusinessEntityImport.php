<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\BusinessEntity;
use App\Models\EmployeeBusinessEntity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class EmployeeBusinessEntityImport implements ToCollection, WithHeadingRow
{
    private $businessEntities;

    public function __construct()
    {
        // Ambil semua badan usaha dari database (ID, Code, dan Name)
        $this->businessEntities = BusinessEntity::get(['id', 'code', 'name']);
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                // Skip jika Employee ID kosong
                if (empty($row['employee_id'])) {
                    continue;
                }

                // Buat atau perbarui data Employee
                $employee = Employee::updateOrCreate(
                    ['employee_id' => $row['employee_id']],
                    ['name' => $row['nama_karyawan']]
                );

                // Hapus semua data gaji lama untuk karyawan ini
                EmployeeBusinessEntity::where('employee_id', $employee->id)->delete();

                // Identifikasi badan usaha utama dari kolom `badan_usaha`
                $primaryBusinessEntity = $this->findPrimaryBusinessEntity($row['badan_usaha']);

                // Loop semua badan usaha yang relevan
                foreach ($this->businessEntities as $businessEntity) {
                    $columnName = strtolower(str_replace(' ', '_', $businessEntity->code)); // Mapping nama kolom

                    if (!isset($row[$columnName])) {
                        continue; // Lewati jika kolom tidak ada
                    }

                    $salary = $this->cleanCurrency($row[$columnName]);

                    // Jika salary lebih dari 0, buat atau perbarui relasi
                    if ($salary > 0) {
                        EmployeeBusinessEntity::create([
                            'employee_id' => $employee->id,
                            'business_entity_id' => $businessEntity->id,
                            'salary' => $salary,
                            'is_primary' => $primaryBusinessEntity && $primaryBusinessEntity->id == $businessEntity->id,
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function findPrimaryBusinessEntity($input)
    {
        if (empty($input)) {
            return null;
        }

        // Cari berdasarkan kode atau nama badan usaha
        return $this->businessEntities->first(function ($businessEntity) use ($input) {
            return strtolower($businessEntity->code) == strtolower($input)
                || strtolower($businessEntity->name) == strtolower($input);
        });
    }

    private function cleanCurrency($value): float
    {
        if (empty($value)) {
            return 0.0;
        }

        // Bersihkan simbol mata uang dan format angka
        $clean = preg_replace('/[Rp\s\.]/', '', $value);
        $clean = str_replace(',', '.', $clean);

        return (float) $clean;
    }
}
