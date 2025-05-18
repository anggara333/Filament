<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorageResource\Pages;
use App\Filament\Resources\StorageResource\RelationManagers;
use App\Models\Storage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class StorageResource extends Resource
{
    protected static ?string $model = Storage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\DatePicker::make('date')
                ->label('Tanggal')
                ->required(),
            Forms\Components\TimePicker::make('time')
                ->label('Waktu')
                ->required(),
            Forms\Components\Select::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->required(),
            Forms\Components\Select::make('product_id')
                ->label('Produk')
                ->relationship('product', 'name')
                ->required(),
            Forms\Components\TextInput::make('amount')
                ->label('Jumlah')
                ->numeric()
                ->required()
                ->afterStateUpdated(function ($state, $set, $get) {
                        $set('total', $state * $get('price'));
                    }),
            Forms\Components\TextInput::make('price')
                ->label('Harga')
                ->numeric()
                ->required()
    ->afterStateUpdated(function ($state, $set, $get) {
                    $set('total', $get('amount') * $state);
                }),
            Forms\Components\TextInput::make('total')
                ->label('Total')
                ->numeric() 
                    ->readOnly()
                    ->default(0),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('date')
                ->label('Tanggal')
                ->date(),
            Tables\Columns\TextColumn::make('time')
                ->label('Waktu'),
            Tables\Columns\TextColumn::make('category.name')->searchable()
                ->label('Kategori'),
            Tables\Columns\TextColumn::make('product.name')->searchable()
                ->label('Produk'),
            Tables\Columns\TextColumn::make('amount')
                ->label('Jumlah')
                ->numeric(),
            Tables\Columns\TextColumn::make('price')
                ->label('Harga')
                ->numeric()
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
        ])
        ->filters([
            // Tambahkan filter jika diperlukan
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
            'index' => Pages\ListStorages::route('/'),
          //  'create' => Pages\CreateStorage::route('/create'),
         //   'edit' => Pages\EditStorage::route('/{record}/edit'),
        ];
    }
}
 