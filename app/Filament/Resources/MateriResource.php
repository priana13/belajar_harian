<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Materi;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\KategoriMateri;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MateriResource\Pages;
use App\Filament\Resources\MateriResource\RelationManagers\SoalRelationManager;
use App\Filament\Resources\MateriResource\RelationManagers\MateriDetailRelationManager;
use Filament\Forms\Components\TextInput;

class MateriResource extends Resource
{
    protected static ?string $model = Materi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationLabel  = 'Materi';

    protected static ?int $navigationSort = 1;

    // protected static ?string $navigationGroup = 'Aktivasi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Grid::make(4)->schema([
                        Forms\Components\TextInput::make('nama_materi')
                            ->required()->helperText('Nama Buku atau Tema Pembahasan')
                            ->maxLength(255)->columnSpan(2),
                        
                        Forms\Components\TextInput::make('urutan')->required()->label("No")->helperText("Nomor Urut Belajar"),
                        Forms\Components\TextInput::make('kode_materi')->unique(ignoreRecord: true),
                        FileUpload::make('image')->acceptedFileTypes(['image/*'])
                            ->label('Cover materi')->visible(false),

                        Select::make('kategori_id')->required()
                            ->options(fn () => KategoriMateri::orderby('nama_kategori')
                                ->pluck('nama_kategori', 'id'))
                            ->label('Kategori Materi'),
                        TextInput::make('materi_per_pekan')->numeric()->required()->maxValue(6)->minValue(1),
                        RichEditor::make('sinopsis')
                            ->required()->columnSpanFull(),                        


                        // Radio::make('type')->required()->options([
                        //     'Pembinaan' => 'Pembinaan',
                        //     'Diklat' => 'Diklat',
                        //     'Umum' => 'Umum'
                        // ])->label('Jenis materi')->required(),

                        // Radio::make('jenis_materi')->options([
                        //     'text' => 'Text',
                        //     'multimedia' => 'Multimedia'
                        // ])->default('multimedia')->label('Jenis konten')
                        //     ->descriptions(['multimedia' => 'Materi berupa audio atau video'])
                        
                    ]),
                
                    Checkbox::make('is_active')->default(true)->label("Aktif")->disabledOn('edit'),
                    Checkbox::make('kelas_intensif')->helperText("Masukan ke dalam kelas yang berututan dari materi 1 dst"),
                    

                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->sortable()->label("No"),
                TextColumn::make('nama_materi')->searchable(),
                TextColumn::make('kode_materi')->searchable()->label('Kode'),
                // TextColumn::make('type')->label('Jenis Materi')->sortable(),
                TextColumn::make('kategori.nama_kategori'),
                TextColumn::make('materi_detail_count')
                    ->counts('materi_detail')->label('Jumlah Pertemuan'),
                TextColumn::make('jenis_materi')->label('Jenis Konten'),
                ImageColumn::make('image')->height(80),
                // ToggleColumn::make('is_active')

            ])
            ->filters([
                SelectFilter::make('type')->options([
                    'Pembinaan' => 'Pembinaan',
                    'Diklat' => 'Diklat',
                    'Umum' => 'Umum'
                ]),
                SelectFilter::make('kategori')->relationship('kategori', 'nama_kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Materi Video')
                    ->icon('heroicon-o-book-open')
                    ->url(fn (Materi $record): string => route('halaman-materi-video', [
                        'kode' => $record->kode_materi,
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MateriDetailRelationManager::class,
            SoalRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMateris::route('/'),
            'create' => Pages\CreateMateri::route('/create'),
            'edit' => Pages\EditMateri::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query;

        // return $query->where('is_active' , true)->orderBy('urutan');
    }


}
