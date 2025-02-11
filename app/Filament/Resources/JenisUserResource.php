<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisUserResource\Pages;
use App\Models\JenisUser;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;


class JenisUserResource extends Resource
{
    protected static ?string $model = JenisUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Konfigurasi';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel  = 'Jenis User';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_jenis')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_jenis')->searchable(),
                TextColumn::make('users_count')->counts("users")->label("Anggota"),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListJenisUsers::route('/'),
            'create' => Pages\CreateJenisUser::route('/create'),
            'edit' => Pages\EditJenisUser::route('/{record}/edit'),
        ];
    }    
}
