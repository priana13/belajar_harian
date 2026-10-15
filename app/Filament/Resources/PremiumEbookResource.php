<?php

namespace App\Filament\Resources;

use App\Models\PremiumEbook;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PremiumEbookResource extends AdminPremiumResource
{
    protected static ?string $model = PremiumEbook::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Ebook Materi';

    protected static ?string $modelLabel = 'Ebook Materi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('materi_id')->label('Materi')->relationship('materi', 'nama_materi')->searchable()->required(),
            TextInput::make('title')->label('Judul')->required()->maxLength(255),
            Textarea::make('description')->label('Deskripsi')->maxLength(5000),
            FileUpload::make('file_path')->label('File PDF')->disk('local')->directory('premium/ebooks')->visibility('private')->acceptedFileTypes(['application/pdf'])->maxSize(51200)->previewable(false)->required(),
            Toggle::make('is_active')->label('Tersedia')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
            Tables\Columns\TextColumn::make('materi.nama_materi')->label('Materi'),
            Tables\Columns\IconColumn::make('is_active')->label('Tersedia')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => PremiumEbookResource\Pages\ManagePremiumEbooks::route('/')];
    }
}
