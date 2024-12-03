<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeBusinessEntitiesRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\SalariesRelationManager;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('primaryBusinessEntity.businessEntity.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grossSalary')
                    ->money('IDR', locale: 'id'),
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
                //
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Grid horizontal dengan 2 kolom utama
                Infolists\Components\Grid::make()
                    ->schema([
                        // Card pertama: Informasi Karyawan
                        Infolists\Components\Card::make()
                            ->label('Informasi Karyawan')
                            ->schema([
                                Infolists\Components\TextEntry::make('employee_id')->label('ID Karyawan'),
                                Infolists\Components\TextEntry::make('name')->label('Nama'),
                                Infolists\Components\TextEntry::make('primaryBusinessEntity.businessEntity.name')->label('Badan Usaha'),
                            ])
                            ->columns(3),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SalariesRelationManager::class,
            EmployeeBusinessEntitiesRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployees::route('/'),
            'view' => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
