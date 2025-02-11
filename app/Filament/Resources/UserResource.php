<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use App\Filament\Resources\UserResource\Widgets\StatsOverview;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\UserResource\RelationManagers\AngkatanUserRelationManager;
use Filament\Forms\Components\Textarea;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // protected static ?string $navigationGroup = 'Peserta';

    protected static ?string $navigationLabel  = 'Peserta';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->placeholder('Nama lengkap'),
                TextInput::make('nip')->placeholder('NIP')->unique(ignoreRecord:true),
                TextInput::make('no_hp'),
                TextInput::make('kode_user')->placeholder('kode_user')->unique(ignoreRecord:true)->default(uniqid()),
                TextInput::make('email')->placeholder('email'),
                TextInput::make('password')
                            ->password()->placeholder('password')                            
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),

                Select::make('jenis_user_id')->relationship('jenis_user', 'nama_jenis'),
                Select::make('jenis_kelamin')->options([
                    "L" => "Laki-laki",
                    "P" => "Perempuan"
                ]),
                TextInput::make('pekerjaan'),
                // Toggle::make('status')->label("Sudah Menikah"),
                Select::make('kategori')->options([
                    "Aktif" => "Aktif",
                    "Tidak Aktif" => "Tidak Aktif",
                    "Keluar" => "Keluar"
                ]),
                Textarea::make('catatan')
                // Select::make('kelompok_id')->relationship('kelompok','nama_kel'),
                //
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('nip')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('no_hp')->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')->searchable(),
                Tables\Columns\TextColumn::make('pekerjaan')->searchable(),
                Tables\Columns\TextColumn::make('jenis_user.nama_jenis')->searchable(),
                Tables\Columns\TextColumn::make('kota')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label("Daftar")->date(),
                Tables\Columns\TextColumn::make('angkatan_user_count')->counts("angkatan_user")->label("Kelas"),
                Tables\Columns\TextColumn::make('ujian_count')->counts("ujian")->label("Ujian"),
                Tables\Columns\BadgeColumn::make('kategori')->colors([
                    'success' => "Aktif"
                ]),


            ])
            ->filters([  
                Filter::make('User Tanpa Angkatan')->query(function(Builder $query, $data){
                   
                    if($data['isActive']){

                        return $query->whereDoesntHave('angkatan');

                    }

                    
                }), 
                Filter::make('User Lama Tanpa Angkatan Aktif')->query(function(Builder $query, $data){
                   
                    if($data['isActive']){

                        return $query->whereDoesntHave('angkatan' , function($query){
                            return $query->where('angkatan.status', 'Aktif');
                        })->has('angkatan');

                    }

                    
                }),
                Filter::make('user_tidak_aktif')->query(function(Builder $query, $data){

                   
                    if($data['isActive']){

                        return $query->withCount('ujian')->having('ujians_count',0);

                    }

                    
                }),             
                SelectFilter::make('jenis_user')->relationship('jenis_user','nama_jenis'), 
                SelectFilter::make('jenis_kelamin')->options([
                    'L' => 'L',
                    'P' => 'P'
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

                SelectFilter::make('kategori')->options([
                   "Aktif" => "Aktif",
                   "Tidak Aktif" => "Tidak Aktif",
                   "Keluar" => "Keluar"
                ]),


            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Impersonate::make(), 
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export')

            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            AngkatanUserRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }   
    
    public static function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }

}
