<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MbsPackageResource\Pages;
use App\Filament\Resources\MbsPackageResource\RelationManagers;
use Priana\Membership\Models\MbsPackage;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MbsPackageResource extends Resource
{
    protected static ?string $model = MbsPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'MBS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('price')->numeric()->required(),
                Forms\Components\TextInput::make('month_qty')->required(),
                Forms\Components\Textarea::make('detail')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('price')->formatStateUsing(fn($state) => number_format($state)),
                TextColumn::make('month_qty'),
                TextColumn::make('detail'),
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
            'index' => Pages\ListMbsPackages::route('/'),
            // 'create' => Pages\CreateMbsPackage::route('/create'),
            // 'edit' => Pages\EditMbsPackage::route('/{record}/edit'),
        ];
    }    
}
