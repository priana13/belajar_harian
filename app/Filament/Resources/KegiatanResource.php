<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KegiatanResource\Pages;
use App\Models\Kegiatan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;


class KegiatanResource extends Resource
{
    protected static ?string $model = Kegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Aktivasi';

    protected static ?string $navigationLabel  = 'Kegiatan';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kategori_id')->relationship('kategori_kegiatan', 'nama')->searchable()->preload(),
                Select::make('type')->options([
                    'Umum' => "Umum", 
                    "Anggota" => "Anggota"
                ]),                
                Forms\Components\DatePicker::make('tgl')
                    ->required(),
                Forms\Components\TextInput::make('tempat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('keterangan')->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori_kegiatan.nama')->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('tempat'),
                Tables\Columns\TextColumn::make('tempat')->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('keterangan')
                
            ])
            ->filters([
               
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
            'index' => Pages\ListKegiatans::route('/'),
            'create' => Pages\CreateKegiatan::route('/create'),
            'edit' => Pages\EditKegiatan::route('/{record}/edit'),
        ];
    }  
    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }


}
