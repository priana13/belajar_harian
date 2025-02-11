<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ujian;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UjianResource\Pages;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\UjianResource\RelationManagers\SoalUjianRelationManager;

class UjianResource extends Resource
{
    protected static ?string $model = Ujian::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    // protected static ?string $navigationGroup = 'Aktivasi';

    protected static ?string $navigationLabel  = 'Ujian';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabledOn('edit'),
                // Forms\Components\TextInput::make('nama_ujian')->required()->maxLength(255),
                Forms\Components\Select::make('jenis_ujian_id')->relationship('jenis_ujian', 'nama'),
                Forms\Components\Select::make('angkatan_id')->relationship('angkatan', 'tanggal_mulai'),
                
                Forms\Components\TextInput::make('nilai'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label("Tanggal")->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label("Peserta")->searchable(),
                // Tables\Columns\TextColumn::make('nama_ujian')->searchable(),
                Tables\Columns\TextColumn::make('jenis_ujian.nama')->sortable(),
                Tables\Columns\TextColumn::make('angkatan.tanggal_mulai'),
                
                Tables\Columns\TextColumn::make('nilai'),
                Tables\Columns\TextColumn::make('nilai_akhir'),
                Tables\Columns\TextColumn::make('ipk'),
                Tables\Columns\TextColumn::make('predikat'),
                Tables\Columns\TextColumn::make('keterangan'),

            ])
            ->filters([
                SelectFilter::make('angkatan')->relationship('angkatan', 'tanggal_mulai'),
                SelectFilter::make('jenis_ujian')->relationship('jenis_ujian', 'nama'),
                SelectFilter::make('user')->relationship('user', 'name')->label("Peserta")->searchable(),
                SelectFilter::make('keterangan')->options([
                    "Lulus" => "Lulus", 
                    "Tidak Lulus" => "Tidak Lulus"
                ]),
                SelectFilter::make('predikat')->options([
                    "Cumlaude" => "Cumlaude", 
                    "Sangat Baik" => "Sangat Baik",
                    "Baik" => "Baik",
                    "Cukup" => "Cukup",
                    "Kurang" => "Kurang"
                ]),

                Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from')->label("Dari Tgl"),
                    Forms\Components\DatePicker::make('created_until')->label("Sampai Tgl"),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
                
                // IPK filter
                Filter::make('ipk')
                ->form([
                    Forms\Components\TextInput::make('from')->label("IPK Dari"),
                    Forms\Components\TextInput::make('until')->label("IPK Sampai"),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $value): Builder => $query->where('ipk', '>=', $value),
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $value): Builder => $query->where('ipk', '<=', $value),
                        );
                }),
                // Filter Nilai

                Filter::make('nilai')
                    ->form([
                        Forms\Components\TextInput::make('from')->label("Nilai Dari"),
                        Forms\Components\TextInput::make('until')->label("Nilai Sampai"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $value): Builder => $query->where('nilai', '>=', $value),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $value): Builder => $query->where('nilai', '<=', $value),
                            );
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export')
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            SoalUjianRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUjians::route('/'),
            'create' => Pages\CreateUjian::route('/create'),
            'edit' => Pages\EditUjian::route('/{record}/edit'),
        ];
    }   
    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }
}
