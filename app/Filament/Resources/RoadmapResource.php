<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Roadmap;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoadmapResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoadmapResource\RelationManagers;
use App\Filament\Resources\RoadmapResource\RelationManagers\MateriRelationManager;

class RoadmapResource extends Resource
{
    protected static ?string $model = Roadmap::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_roadmap')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('detail')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_roadmap'),                
                Tables\Columns\TextColumn::make('materi_count')->counts('materi')->label("Materi"),
                Tables\Columns\TextColumn::make('detail'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("Jadwal")->url(function($record){
                    return route('filament.resources.roadmaps.buat-jadwal', $record);
                })->icon('heroicon-o-calendar'),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            MateriRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoadmaps::route('/'),
            'create' => Pages\CreateRoadmap::route('/create'),
            'edit' => Pages\EditRoadmap::route('/{record}/edit'),
            'buat-jadwal' => Pages\BuatJadwalRoadmap::route('/buat-jadwal/{record}'),
        ];
    }    
}
