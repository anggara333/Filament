<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->required(),
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->default(function () {
                        return 'INV-' . Str::random(12); 
                    })
                    ->required()
                    ->hidden(false), 
                Forms\Components\TextInput::make('price')
                    ->label('Satuan')
                    ->numeric()
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $set('total', $state * $get('quantity'));
                    }),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $set('total', $get('price') * $state);
                    }),
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->numeric() 
                    ->readOnly()
                    ->default(0),
                Forms\Components\TextInput::make('send')
                    ->label('Pengirim')
                    ->autocomplete('off')
                    ->required(),
                Forms\Components\TextInput::make('reciv')
                    ->label('Penerima')
                    ->autocomplete('off')   
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')->searchable()
                    ->label('Client'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi'),
                Tables\Columns\TextColumn::make('invoice_number')->searchable()
                    ->label('Nomor Invoice'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Satuan')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                        ->icon('heroicon-o-document')
                        ->action(function ($record) {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.transaction', compact('record'));
                            return response()->streamDownload(
                                fn () => print($pdf->stream()),
                                "transaction-" . $record->invoice_number . ".pdf"
                            );
                        }),        
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
            'index' => Pages\ListTransactions::route('/'),
        //    'create' => Pages\CreateTransaction::route('/create'),
        //    'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    public static function afterCreate($record, $data)
    {
        $record->update([
        'invoice_number' => 'INV-' . Str::random(12),
            ]);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            $transaction->total = $transaction->price * $transaction->quantity;
        });
    }
}
