<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MbsSubscriptionResource\Pages;
use App\Filament\Resources\MbsSubscriptionResource\RelationManagers;
use Priana\Membership\Models\MbsSubscription;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MbsSubscriptionResource extends Resource
{
    protected static ?string $model = MbsSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'MBS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("user_id")->relationship('user' , 'name')->searchable()->preload(),
                Forms\Components\Select::make("mbs_package_id")->relationship('package' , 'name')->searchable()->preload(),
                Forms\Components\DatePicker::make('expired_at'),
                Forms\Components\Select::make('status')->options([
                    'Active' => "Active",
                    'Expired' => "Expired",
                ])->default("Active"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('package.name'),
                Tables\Columns\TextColumn::make('expired_at'),
                Tables\Columns\BadgeColumn::make('status')->label('Status')->colors([
                    "success" => "Active"
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMbsSubscriptions::route('/'),
            // 'create' => Pages\CreateMbsSubscription::route('/create'),
            // 'edit' => Pages\EditMbsSubscription::route('/{record}/edit'),
        ];
    }    
}
