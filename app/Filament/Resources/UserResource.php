<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    
    public static function form(Form $form): Form
    {
    return $form
        ->schema([

            //card
            Forms\Components\Card::make()
                ->schema([

                    //name
                    Forms\Components\TextInput::make('name')
                      ->label('User Name')
                      ->placeholder('User Name')
                      ->autocomplete('off')
                      ->required(),

                    //description
                    Forms\Components\TextInput::make('email')
                      ->label('Email')
                      ->placeholder('Email')
                      ->autocomplete('off')
                      ->required(),
        
                    //description
                    Forms\Components\TextInput::make('password')
                      ->label('Password')
                      ->placeholder('Password')
                      ->autocomplete('off')
                      ->required(),
                    
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email'),
        ])
        ->filters([
            //
        ])
        ->actions([
          //  Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
           // Tables\Actions\DeleteBulkAction::make(),
        ]),
    ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function canCreate(): bool 
    {
        return false; 
    } 

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
         //   'create' => Pages\CreateUser::route('/create'),
         //   'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
