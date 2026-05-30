<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SertifikatUserResource\Pages;
use App\Filament\Resources\SertifikatUserResource\RelationManagers;
use App\Models\SertifikatUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SertifikatUserResource extends Resource
{
    protected static ?string $model = SertifikatUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ujian_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('sertifikat_id')
                    ->required()
                    ->relationship('sertifikat', 'nama'),
                Forms\Components\Select::make('materi_id')
                    ->required()
                    ->relationship('materi', 'nama_materi'),
                Forms\Components\TextInput::make('predikat')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('tanggal')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(191),
                Forms\Components\FileUpload::make('ttd_image')
                    ->image(),
                Forms\Components\TextInput::make('ttd_nama')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('ttd_jabatan')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('ttd_nama2')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('ttd_jabatan2')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('ttd_image2')
                    ->maxLength(191)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ujian_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sertifikat_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('materi.nama_materi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('predikat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('ttd_image'),
                Tables\Columns\TextColumn::make('ttd_nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ttd_jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ttd_nama2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ttd_jabatan2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ttd_image2')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('materi_id')->relationship('materi', 'nama_materi')->label("Mater"),
                SelectFilter::make('predikat')->options([
                    'Kurang' => 'Kurang',
                    'Cukup' => 'Cukup',
                    'Baik' => 'Baik',
                    'Sangat Baik' => 'Sangat Baik',
                    'Cumlaude' => 'Cumlaude'
                ])->label("Predikat"),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('lihat')->url(fn (SertifikatUser $record): string => route('sertifikat', $record->code))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListSertifikatUsers::route('/'),
            'create' => Pages\CreateSertifikatUser::route('/create'),
            'edit' => Pages\EditSertifikatUser::route('/{record}/edit'),
        ];
    }
}
