<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\AbsensiKegiatan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Filament\Resources\AbsensiKegiatanResource\Pages;
use App\Models\Kegiatan;

class AbsensiKegiatanResource extends Resource
{
    // use Translatable;
    protected static ?string $model = AbsensiKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel  = 'Absensi';

    protected static ?int $navigationSort = 6;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()->label('Nama User'),
                Select::make('kegiatan_id')
                    ->options(Kegiatan::all()->pluck('nama', 'id'))
                    ->searchable()->label('Nama Kegiatan'),
                Select::make('status_kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'tidak_hadir' => 'Tidak Hadir'
                    ])
                    ->default('hadir'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('kegiatan')->formatStateUsing(function($state){

                    return $state;
                }),
                Tables\Columns\TextColumn::make('kegiatan.type')->label('Jenis Kegiatan'),

                Tables\Columns\TextColumn::make('status_kehadiran')
                    ->label('Status kehadiran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label('Waktu')
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

    // public static function getTranslatableLocales(): array
    // {
    //     return ['en', 'in'];
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensiKegiatans::route('/'),
            // 'create' => Pages\CreateAbsensiKegiatan::route('/create'),
            // 'edit' => Pages\EditAbsensiKegiatan::route('/{record}/edit'),
        ];
    }
}
