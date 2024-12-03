<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Filament\Resources\SalaryResource;
use App\Imports\SalaryImport;
use App\Models\Salary;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;

class ManageSalaries extends ManageRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExcelImportAction::make()
                ->color("success")
                ->use(SalaryImport::class),
            Actions\Action::make('export')
                ->color("success")
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Select::make('periode')
                        ->label('Periode')
                        ->native(false)
                        ->options(Salary::query()->distinct()->orderBy('periode', 'desc')->pluck('periode', 'periode'))
                        ->required(),
                ])
                ->modalWidth('md')
                ->modalHeading('Export Data')
                ->modalSubheading('Pilih periode untuk export data')
                ->modalButton('Export')
                ->action(function (array $data) {
                    // After form is submitted, redirect to the export route
                    return redirect()->route('export-salary', ['periode' => $data['periode']]);
                }),
        ];
    }
}
