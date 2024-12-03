<?php

namespace App\Filament\Resources\EmployeeBusinessEntityResource\Pages;

use App\Filament\Resources\EmployeeBusinessEntityResource;
use App\Imports\EmployeeBusinessEntityImport;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployeeBusinessEntities extends ManageRecords
{
    protected static string $resource = EmployeeBusinessEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExcelImportAction::make()
                ->sampleExcel(
                    sampleData: [
                        ['Employee ID' => 'EX005', 'Nama Karyawan' => 'ADEL', 'Badan Usaha' => 'PT Media Selular Indonesia', 'Gaji Gross' => 5500000, 'MKLI' => 1000000, 'RISM' => 1000000, 'MAJU' => 0, 'CVTOP' => 1500000, 'MSI' => 1500000, 'AAN/CS' => 500000, 'TOTAL' => 5500000],
                        ['Employee ID' => 'EX009', 'Nama Karyawan' => 'WATI', 'Badan Usaha' => 'CV Top Selular', 'Gaji Gross' => 12000000, 'MKLI' => 2000000, 'RISM' => 0, 'MAJU' => 2000000, 'CVTOP' => 8000000, 'MSI' => 0, 'AAN/CS' => 12000000],
                        ['Employee ID' => 'EX004', 'Nama Karyawan' => 'LOLI', 'Badan Usaha' => 'CV Top Selular', 'Gaji Gross' => 5885000, 'MKLI' => 1000000, 'RISM' => 0, 'MAJU' => 4885000, 'CVTOP' => 0, 'MSI' => 0, 'AAN/CS' => 5885000],
                        ['Employee ID' => 'EX008', 'Nama Karyawan' => 'KOMAR', 'Badan Usaha' => 'PT Media Selular Indonesia', 'Gaji Gross' => 6400000, 'MKLI' => 1600000, 'RISM' => 1600000, 'MAJU' => 0, 'CVTOP' => 1600000, 'MSI' => 1600000, 'AAN/CS' => 0, 'TOTAL' => 6400000],
                    ],
                    fileName: 'sample.xlsx',
                    sampleButtonLabel: 'Download Sample',
                    customiseActionUsing: fn(Action $action) => $action->color('success'),
                )
                ->color("success")
                ->use(EmployeeBusinessEntityImport::class),
        ];
    }
}
