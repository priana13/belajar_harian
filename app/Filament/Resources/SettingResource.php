<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Konfigurasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->disabledOn('edit')
                    ->live()
                    ->maxLength(191),
                Forms\Components\FileUpload::make('value')
                    ->label('Logo')
                    ->image()
                    ->disk('public')
                    ->directory('settings')
                    ->visible(fn (Get $get): bool => $get('key') === 'logo')
                    ->dehydrated(fn (Get $get): bool => $get('key') === 'logo')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('value')
                    ->visible(fn (Get $get): bool => $get('key') !== 'logo')
                    ->dehydrated(fn (Get $get): bool => $get('key') !== 'logo')
                    ->maxLength(65535)->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required()->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('value')
                    ->html()
                    ->formatStateUsing(fn ($record, $state) => $record->key === 'logo' && $state
                        ? '<img src="' . asset('storage/' . $state) . '" class="h-10 w-auto" alt="logo">'
                        : $state),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }    
}
