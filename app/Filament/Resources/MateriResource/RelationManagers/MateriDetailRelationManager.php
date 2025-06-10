<?php

namespace App\Filament\Resources\MateriResource\RelationManagers;

use Closure;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\FuncCall;

class MateriDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'materi_detail';

    protected static ?string $recordTitleAttribute = 'pertemuan';

    protected static ?string $title = 'Pertemuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(6)
                    ->schema([
                        Forms\Components\TextInput::make('pertemuan')->required()->numeric(),
                        Forms\Components\TextInput::make('judul')->required()->maxLength(255)->columnSpan(5),
                        Forms\Components\Select::make('jenis_kontent')->required()->options([
                            "Audio" => "Audio",
                           // "Text" => "Text",
                            "Video" => "Video",
                        ])->reactive(),
                        RichEditor::make('isi')->maxWidth(100)->visible(false),
                        FileUpload::make('thumbnail')->acceptedFileTypes(['image/*'])->visible(false),
                        FileUpload::make('multimedia_url')
                        ->directory(function($record){

                            $materi = $record->materi;
                            $pertemuan = $record->pertemuan;

                            $dir = $materi->kode_materi;
                            
                            return $dir;
                        })
                        ->preserveFilenames()
                        ->visible(function($get) {
                            return $get('jenis_kontent') == 'Audio';
                        } )->label("Materi/Audio")->maxSize(551200)->afterStateUpdated(fn (callable $set, $state) => $set('mime', $state?->getMimeType()))->columnSpanFull(),

                        TextInput::make('video_url')->visible(function($get) {
                            return $get('jenis_kontent') == 'Video';
                        } )->label("Url Video")->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pertemuan')->searchable(),
                TextColumn::make('judul')->limit(50),
                TextColumn::make('jenis_kontent'),
                ImageColumn::make('multimedia_url')->label('Audio')->view('tables.columns.kolom-materi'),
                TextColumn::make('video_url')->label('Video')->url(fn ($record) => $record->video_url )->openUrlInNewTab()->view('tables.columns.kolom-video'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }



    protected function getTableQuery(): Builder | Relation
    {
        if (! $this instanceof HasRelationshipTable) {
            $livewireClass = static::class;

            throw new Exception("Class [{$livewireClass}] must define a [getTableQuery()] method.");
        }

        $relationship = $this->getRelationship();

        $query = $relationship->getQuery();

        if ($relationship instanceof HasManyThrough) {
            // https://github.com/laravel/framework/issues/4962
            $query->select($query->getModel()->getTable() . '.*');

            return $query;
        }

        if ($relationship instanceof BelongsToMany) {
            // https://github.com/laravel/framework/issues/4962
            if (! $this->allowsDuplicates()) {
                $this->selectPivotDataInQuery($query);
            }

            // https://github.com/filamentphp/filament/issues/2079
            $query->withCasts(
                app($relationship->getPivotClass())->getCasts(),
            );
        }

        return $query->orderBy('pertemuan');
    }


}
