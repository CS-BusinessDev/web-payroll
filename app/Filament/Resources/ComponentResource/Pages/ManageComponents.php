<?php

namespace App\Filament\Resources\ComponentResource\Pages;

use App\Filament\Resources\ComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageComponents extends ManageRecords
{
    protected static string $resource = ComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
