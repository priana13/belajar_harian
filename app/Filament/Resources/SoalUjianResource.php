<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoalUjianResource\Pages;
use App\Filament\Resources\SoalUjianResource\RelationManagers;
use App\Filament\Resources\SoalUjianResource\Widgets\SoalUjianOverview;
use App\Models\SoalUjian;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Layout;



class SoalUjianResource extends Resource
{
    protected static ?string $model = SoalUjian::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-alt';

    protected static ?string $navigationLabel  = 'Soal Ujian';

    protected static bool $shouldRegisterNavigation = false;

    // protected static ?string $navigationGroup = 'Soal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('soal_id')->label('Soal')
                    ->required(),
                TextInput::make('ujian_id')->label('Ujian')
                    ->required(),
                Select::make('user_id')->relationship('user', 'name'),
                TextInput::make('jawaban')
                    ->required()
                    ->maxLength(255),
                Toggle::make('istrue')->label('Benarkah?')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('soal_id')->searchable()->label('Soal'),
                Tables\Columns\TextColumn::make('ujian_id')->label('Ujian'),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('jawaban'),
                Tables\Columns\IconColumn::make('istrue')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')
                    ->dateTime()
            ])
            ->filters([
                //
            ],
            layout: Layout::AboveContent,
            )
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
            RelationManagers\UserRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoalUjians::route('/'),
            // 'create' => Pages\CreateSoalUjian::route('/create'),
            'edit' => Pages\EditSoalUjian::route('/{record}/edit'),
        ];
    }    


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();      
    
        return $data;
    }

    public static function getWidgets(): array
    {
        return [
            SoalUjianOverview::class
        ];
    }


}
