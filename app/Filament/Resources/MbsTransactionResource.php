<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Faker\Core\Number;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Priana\Membership\Models\MbsTransaction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MbsTransactionResource\Pages;
use App\Filament\Resources\MbsTransactionResource\RelationManagers;


class MbsTransactionResource extends Resource
{
    protected static ?string $model = MbsTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'MBS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('package.name'),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('price')->formatStateUsing(fn($state) => number_format($state)),
                Tables\Columns\TextColumn::make('qty'),
                // Tables\Columns\TextColumn::make('unique_code'),
                Tables\Columns\TextColumn::make('total_price')->formatStateUsing(fn($state) => number_format($state)),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'success' => 'Completed',
                    'warning' => "Pending"
                ]),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                    SelectFilter::make('status')->options([
                        "Pending" => "Pending",
                        "Completed" => "Completed",
                        "Expired" => "Expired",
                    ]),
                    SelectFilter::make("package")->relationship('package' , 'name'),
                    SelectFilter::make("user")->relationship('user' , 'name' , function($query){
                        return $query->whereHas('transactions');
                    })->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make("Selesaikan")->action("selesaikan")
                                        ->visible(fn($record) => $record->status == 'Pending'),
                                        // ->requiresConfirmation(),
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
            'index' => Pages\ListMbsTransactions::route('/'),
            'create' => Pages\CreateMbsTransaction::route('/create'),
            'edit' => Pages\EditMbsTransaction::route('/{record}/edit'),
        ];
    }    
}
