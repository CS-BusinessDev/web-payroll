<?php

namespace App\Filament\Resources\SalaryDetailResource\Pages;

use App\Filament\Resources\SalaryDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSalaryDetails extends ManageRecords
{
    protected static string $resource = SalaryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
