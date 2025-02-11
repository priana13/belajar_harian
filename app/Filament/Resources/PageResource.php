<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Konfigurasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                ->required()
                ->maxLength(191),
                Forms\Components\TextInput::make('slug')->unique(ignoreRecord:true)
                    ->required()->maxLength(191),               
                Forms\Components\RichEditor::make('konten')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('type')
                    ->required()->options([
                        "Info" => "Info", 
                        "FAQ" => "FAQ",
                        "Profile" => "Profile"
                    ]),

                Forms\Components\FileUpload::make('image'),
                Forms\Components\Toggle::make('is_publish')
                    ->required(),
                Fieldset::make('Tombol')->schema([
                    Forms\Components\TextInput::make('action_url')->label('Url'),
                    Forms\Components\TextInput::make('action_label')->label("Text"),
                ])
                
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul'),
                Tables\Columns\TextColumn::make('slug'),               
                // Tables\Columns\TextColumn::make('konten'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\IconColumn::make('is_publish')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("lihat")->color('success')
                    ->url(function($record){

                        return url('/page/' . $record->slug);
                    })->openUrlInNewTab(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }    
}
