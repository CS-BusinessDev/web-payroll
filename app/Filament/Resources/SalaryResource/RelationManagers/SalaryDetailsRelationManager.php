<?php

namespace App\Filament\Resources\SalaryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'salaryDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('salary_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('salary_id')
            ->columns([
                Tables\Columns\TextColumn::make('component.name'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR', locale: 'id')
                    ->summarize(Sum::make()
                        ->label('Total')
                        ->money('IDR', locale: 'id')),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function getTabs(): array
    {
        return [
            'allowance' => Tab::make('Allowance')
                ->query(fn(Builder $query) => $query->whereHas('component', fn(Builder $query) => $query->where('type', 'allowance'))),
            'deduction' => Tab::make('Deduction')
                ->query(fn(Builder $query) => $query->whereHas('component', fn(Builder $query) => $query->where('type', 'deduction'))),
        ];
    }
}
