<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceResource\Pages;
use App\Filament\Resources\FinanceResource\RelationManagers;
use App\Models\Finance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\ListRecords;

class FinanceResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\DatePicker::make('date')
                        ->label('Tanggal')
                        ->placeholder('Pilih Tanggal')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->placeholder('Deskripsi')
                        ->autocomplete('off')
                        ->rows(5)
                        ->required(),
                    Forms\Components\TextInput::make('kredit')
                        ->label('Debit')
                        ->placeholder('Jumlah uang keluar')
                        ->numeric()
                        ->autocomplete('off')
                        ->required()
                        ->afterStateUpdated(function ($state, $set, $get) {
                                $set('balance', $get('debit') - $state);
                        }),
                    Forms\Components\TextInput::make('debit')
                        ->label('Kredit')
                        ->placeholder('Jumlah uang masuk')
                        ->numeric()
                        ->autocomplete('off')
                        ->required()
                        ->afterStateUpdated(function ($state, $set, $get) {
                                $set('balance', $state - $get('kredit'));
                        }),
                     Forms\Components\TextInput::make('balance')
                        ->label('Saldo')
                        ->placeholder('Saldo')
                        ->numeric()
                        ->autocomplete('off')
                        ->nullable()
                        ->readOnly(),
                ])
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('date')
                ->label('Tanggal')
                ->date(),
            Tables\Columns\TextColumn::make('description')
                ->label('Deskripsi')
                ->searchable(),
            Tables\Columns\TextColumn::make('kredit')
                ->label('Debit')
                ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '-'),
            Tables\Columns\TextColumn::make('debit')
                ->label('Kredit')
                ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '-'),
            Tables\Columns\TextColumn::make('balance')
                ->label('Saldo')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinances::route('/'),
         //   'create' => Pages\CreateFinance::route('/create'),
       //      'edit' => Pages\EditFinance::route('/{record}/edit'),
        ];
    }
}
