<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalaryResource\Pages;
use App\Filament\Resources\SalaryResource\RelationManagers;
use App\Models\Salary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required(),
                Forms\Components\TextInput::make('basic_salary')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_allowances')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_deductions')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('take_home_pay')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('periode')
                    ->required()
                    ->maxLength(7),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.employee_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee.primaryBusinessEntity.businessEntity.name'),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('total_allowances')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('total_deductions')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('take_home_pay')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('periode')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('periode')
                    ->searchable()
                    ->options(function () {
                        // Menampilkan periode unik berdasarkan data gaji yang ada
                        return Salary::selectRaw('DISTINCT periode')->pluck('periode', 'periode');
                    })
                    ->label('Periode Gaji'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSalaries::route('/'),
        ];
    }
}
