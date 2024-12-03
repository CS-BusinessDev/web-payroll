<?php

namespace App\Imports;

use App\Models\Component;
use App\Models\Employee;
use App\Models\Salary;
use App\Models\SalaryDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SalaryImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // Menghapus header (baris pertama)
        $collection->shift();

        foreach ($collection as $row) {
            // Periksa apakah employee_id ada dan tidak null
            if (empty($row[0])) {
                // Lewati baris ini jika employee_id kosong atau null
                continue;
            }

            try {
                // Cari data Employee berdasarkan employee_id
                $employee = Employee::where('employee_id', $row[0])
                    ->firstOrFail();

                // Buat data Salary
                $salary = Salary::create([
                    'employee_id' => $employee->id,                // Employee ID
                    'basic_salary' => (float) $row[3] ?? 0,        // Basic Salary
                    'total_allowances' => (float) $row[17] ?? 0,   // Total Tunjangan Lainnya
                    'total_deductions' => (float) $row[35] ?? 0,   // Total Potongan
                    'take_home_pay' => (float) $row[36] ?? 0,      // Take Home Pay (kolom ke-37)
                    'periode' => $this->getPeriode($row),          // Periode
                ]);

                // Mengelola Allowances
                $allowanceColumns = [
                    4 => 'Izin Menginap',
                    5 => 'Tunjangan Operational',
                    6 => 'Tunjangan Kinerja',
                    7 => 'Tunjangan Dinas',
                    8 => 'Tunjungan Kehadiran',
                    9 => 'Tunjangan Makan',
                    10 => 'Tunjangan Jabatan',
                    11 => 'Tunjangan Komunikasi',
                    12 => 'Tunjangan Lain-Lain',
                    13 => 'Tunjangan Masa Kerja',
                    14 => 'Tunjangan Full Shift',
                    15 => 'Overtime',
                ];

                // Menyimpan allowance ke SalaryDetail
                foreach ($allowanceColumns as $index => $name) {
                    $amount = (float) $row[$index] ?? 0;

                    if ($amount > 0) {
                        // Cari atau buat komponen allowance
                        $component = Component::firstOrCreate([
                            'name' => $name,
                            'type' => 'allowance',
                        ]);

                        // Simpan detail gaji untuk allowance dengan salary_id
                        SalaryDetail::create([
                            'salary_id' => $salary->id, // Memastikan salary_id dimasukkan
                            'component_id' => $component->id,
                            'amount' => $amount,
                        ]);
                    }
                }

                // Mengelola Deductions
                $deductionColumns = [
                    16 => 'Tax Allowance',
                    18 => 'Potongan Lain-lain',
                    19 => 'Potongan Kartu Halo',
                    20 => 'Potongan Catering',
                    21 => 'Potongan NBH',
                    22 => 'Potongan SMS',
                    23 => 'Potongan Ketidakhadiran',
                    24 => 'POTONGAN PPH',
                    25 => 'Potongan Keterlambatan New',
                    26 => 'Denda SAM',
                    27 => 'Kredit HP',
                    28 => 'KOPERASI',
                    29 => 'Cicilan NBH',
                    30 => 'Jaminan Pensiun Employee',
                    31 => 'BPJS K Employee',
                    32 => 'BPJS K Family',
                    33 => 'JHT Employees',
                    34 => 'PPH 21'
                ];

                // Menyimpan deduction ke SalaryDetail
                foreach ($deductionColumns as $index => $name) {
                    $amount = (float) $row[$index] ?? 0;

                    if ($amount > 0) {
                        // Cari atau buat komponen deduction
                        $component = Component::firstOrCreate([
                            'name' => $name,
                            'type' => 'deduction',
                        ]);

                        // Simpan detail gaji untuk deduction dengan salary_id
                        SalaryDetail::create([
                            'salary_id' => $salary->id, // Memastikan salary_id dimasukkan
                            'component_id' => $component->id,
                            'amount' => $amount,
                        ]);
                    }
                }

            } catch (\Exception $e) {
                // Log error jika ada masalah dengan baris ini
                \Log::error("Error processing row: " . $e->getMessage());
                // Skip ke baris berikutnya
                continue;
            }
        }
    }

    /**
     * Get the periode in 'Y-m' format.
     *
     * @param array $row
     * @return string
     */
    private function getPeriode($row): string
    {
        // Ambil periode dari data atau gunakan tanggal saat ini
        return date('Y-m');
    }
}
