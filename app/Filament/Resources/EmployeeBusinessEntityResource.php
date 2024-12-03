<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeBusinessEntityResource\Pages;
use App\Filament\Resources\EmployeeBusinessEntityResource\RelationManagers;
use App\Models\EmployeeBusinessEntity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeBusinessEntityResource extends Resource
{
    protected static ?string $model = EmployeeBusinessEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required(),
                Forms\Components\Select::make('business_entity_id')
                    ->relationship('businessEntity', 'name')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Toggle::make('is_primary')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('businessEntity.name'),
                Tables\Columns\TextColumn::make('salary')
                    ->money('IDR', locale: 'id')
                    ->summarize(Sum::make()
                        ->label('Gaji Gross')),
                Tables\Columns\IconColumn::make('is_primary')
                    ->boolean()
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
            ->groups([
                Group::make('employee.name')
                    ->label('Karyawan')
                    ->collapsible(),
            ])
            ->defaultGroup('employee.name')
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


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployeeBusinessEntities::route('/'),
        ];
    }
}
