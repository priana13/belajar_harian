<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SertifikatResource\Pages;
use App\Filament\Resources\SertifikatResource\RelationManagers;
use App\Models\Sertifikat;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SertifikatResource extends Resource
{
    protected static ?string $model = Sertifikat::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Konfigurasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100),
                Forms\Components\FileUpload::make('bg')
                    ->required(),   
                Fieldset::make('Tanda Tangan 1')
                    ->schema([

                        Forms\Components\TextInput::make('ttd_nama')
                            ->label('Nama TTD 1')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ttd_jabatan')
                            ->label('Jabatan TTD 1')
                            ->maxLength(100),
                        Forms\Components\FileUpload::make('ttd_image')
                            ->label('Gambar TTD 1'),

                    ]),       
                    
                    Fieldset::make('Tanda Tangan 2')
                    ->schema([

                        Forms\Components\TextInput::make('ttd_nama2')
                            ->label('Nama TTD 2')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ttd_jabatan2')
                            ->label('Jabatan TTD 2')
                            ->maxLength(100),
                        Forms\Components\FileUpload::make('ttd_image2')
                            ->label('Gambar TTD 2'),
                    ]),
                
                    Forms\Components\Select::make('status')
                        ->options([
                            'Aktif' => 'Aktif',
                            'Tidak Aktif' => 'Tidak Aktif',
                        ])
                        ->default('Aktif')
                        ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('bg'),   
                Tables\Columns\TextColumn::make('status')->searchable()->sortable(),            
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSertifikats::route('/'),
            // 'create' => Pages\CreateSertifikat::route('/create'),
            // 'edit' => Pages\EditSertifikat::route('/{record}/edit'),
        ];
    }    
}
