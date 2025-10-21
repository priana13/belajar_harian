<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\JadwalUjian;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JadwalUjianResource\Pages;
use App\Filament\Resources\JadwalUjianResource\RelationManagers;
use App\Filament\Resources\JadwalUjianResource\RelationManagers\SoalRelationManager;
use Dompdf\FrameDecorator\Text;
use Filament\Forms\Components\TextInput;

class JadwalUjianResource extends Resource
{
    protected static ?string $model = JadwalUjian::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                ->required()
                ->options([
                    "Harian" => "Harian",
                    "Pekanan" => "Pekanan",
                    "Akhir" => "Akhir"
                ]),
                Forms\Components\TextInput::make('urutan')->numeric()
                ->required(),
                Forms\Components\DateTimePicker::make('tanggal'),
                Forms\Components\Select::make('angkatan_id')->relationship('angkatan', 'kode_angkatan')->preload(),
                
                // Select::make('soal_id')
                // ->multiple()
                // ->relationship('soal', 'judul')->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date("D, d M Y")->sortable(),
                Tables\Columns\TextColumn::make('angkatan.kode_angkatan')->searchable(),
                Tables\Columns\TextColumn::make('roadmap.nama_roadmap')->label("Roadmap"),
                Tables\Columns\TextColumn::make('gelombang.gel')->label("Gelombang"),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('urutan')->label("Hari/Pekan")->searchable(),
                Tables\Columns\TextColumn::make('soal_ujian_count')->counts('soal_ujian')->label("Jumlah Soal"),
            ])
            ->filters([
                SelectFilter::make('angkatan')->relationship('angkatan', 'kode_angkatan'),
                SelectFilter::make('type')->options([
                    "Pekanan" => "Pekanan",
                    "Harian" => "Harian",
                    "Akhir" => "Akhir"
                ]),
                SelectFilter::make('roadmap')->relationship('roadmap', 'nama_roadmap'),
                SelectFilter::make('gelombang')->relationship('gelombang', 'gel'),
                // tanggal filter
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
               
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
            SoalRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalUjians::route('/'),
            'create' => Pages\CreateJadwalUjian::route('/create'),
            'edit' => Pages\EditJadwalUjian::route('/{record}/edit'),
        ];
    }   


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->orderByDesc('tanggal');
    }
}
