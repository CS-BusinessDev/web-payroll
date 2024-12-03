<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Accounting;

class SalaryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $periode;

    // Menerima parameter periode pada konstruktor
    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    /**
     * Mengambil data salary yang akan diekspor
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data salary sesuai dengan periode yang diminta
        $salaries = Salary::with('employee.employeeBusinessEntities.businessEntity') // Eager load employee dan business entity
            ->when($this->periode, function ($query) {
                return $query->where('periode', $this->periode);
            })
            ->get();

        // Ambil semua Business Entities
        $businessEntities = \App\Models\BusinessEntity::all();

        // Menyusun hasil dalam format yang diinginkan
        $result = collect();

        // Menambahkan header kolom Business Entities dan data gaji
        $headers = ['ID Karyawan', 'Nama Karyawan', 'Badan Usaha', 'Gross Salary', 'Basic Salary', 'Total Allowances', 'Total Deductions', 'Take Home Pay', 'Period'];
        foreach ($businessEntities as $businessEntity) {
            $headers[] = $businessEntity->code;  // Nama Business Entity jadi header kolom
        }

        $result->push($headers);  // Tambahkan header ke dalam koleksi

        // Menyusun data untuk setiap karyawan yang memiliki periode yang sesuai
        $employees = \App\Models\Employee::all();
        foreach ($employees as $employee) {
            // Ambil data Salary untuk periode yang ditentukan
            $salaryData = $salaries->where('employee_id', $employee->id)->first();

            if ($salaryData) {
                // Pastikan hanya menampilkan data untuk periode yang sesuai
                if ($salaryData->periode == $this->periode) {
                    // Hitung selisih antara gross salary dan take home pay
                    $grossSalary = $employee->grossSalary();
                    $takeHomePay = $salaryData->take_home_pay;
                    $adjustment = max(0, $grossSalary - $takeHomePay); // Selisih yang perlu dikurangi

                    $row = [
                        $employee->employee_id,
                        $employee->name,
                        $employee->primaryBusinessEntity->businessEntity->name,
                        $grossSalary,
                        $salaryData->basic_salary,
                        $salaryData->total_allowances,
                        $salaryData->total_deductions,
                        $takeHomePay,
                        $salaryData->periode,
                    ];

                    // Menambahkan gaji karyawan per Business Entity
                    foreach ($businessEntities as $businessEntity) {
                        // Cek apakah karyawan memiliki gaji di Business Entity ini
                        $salary = $employee->employeeBusinessEntities()
                            ->where('business_entity_id', $businessEntity->id)
                            ->first();

                        if ($businessEntity->id === $employee->primaryBusinessEntity->businessEntity->id) {
                            // Jika ini adalah primary Business Entity, kurangi salary dengan selisih
                            $salaryAmount = $salary ? $salary->salary - $adjustment : 0;
                            $row[] = $salaryAmount > 0 ? $salaryAmount : 0;  // Pastikan salary tidak negatif
                        } else {
                            // Jika bukan primary Business Entity, tetapkan salary
                            $row[] = $salary ? $salary->salary : 0;
                        }
                    }

                    $result->push($row);
                }
            }
        }

        return $result;
    }

    /**
     * Menentukan header untuk kolom
     *
     * @return array
     */
    public function headings(): array
    {
        // Kita sudah menambahkan header kolom dalam metode collection
        return [];
    }

    public function styles($sheet)
    {
        // Format untuk kolom yang berisi nilai gaji
        $columnsWithSalary = ['D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P']; // Kolom yang berisi gaji dan nilai uang
        foreach ($columnsWithSalary as $col) {
            // Gunakan Accounting Wizard untuk format mata uang dengan simbol Rp dan locale ID
            $localeCurrencyMask = new Accounting('Rp', locale: 'id_ID'); // locale untuk Indonesia

            // Terapkan format ke seluruh kolom
            $sheet->getStyle($col)
                ->getNumberFormat()
                ->setFormatCode($localeCurrencyMask->format()); // Terapkan format di sini
        }

        // Menebalkan header
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        return [];
    }
}
