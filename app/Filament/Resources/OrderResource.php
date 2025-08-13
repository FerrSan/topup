<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Sales';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_no')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'PENDING' => 'Pending',
                                'WAITING_PAYMENT' => 'Waiting Payment',
                                'PAID' => 'Paid',
                                'PROCESSING' => 'Processing',
                                'SUCCESS' => 'Success',
                                'FAILED' => 'Failed',
                                'REFUNDED' => 'Refunded',
                                'EXPIRED' => 'Expired',
                                'CANCELLED' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable(),
                        Forms\Components\Select::make('game_id')
                            ->relationship('game', 'name')
                            ->disabled(),
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->disabled(),
                        Forms\Components\TextInput::make('qty')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Player Information')
                    ->schema([
                        Forms\Components\TextInput::make('player_uid'),
                        Forms\Components\TextInput::make('player_server'),
                        Forms\Components\TextInput::make('player_name'),
                        Forms\Components\Textarea::make('buyer_note')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('discount')
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('fee')
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('grand_total')
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('payment_provider')
                            ->disabled(),
                        Forms\Components\TextInput::make('payment_method')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->default('Guest'),
                Tables\Columns\TextColumn::make('game.name')
                    ->limit(20),
                Tables\Columns\TextColumn::make('product.name')
                    ->limit(20),
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'PENDING',
                        'warning' => 'WAITING_PAYMENT',
                        'info' => 'PAID',
                        'primary' => 'PROCESSING',
                        'success' => 'SUCCESS',
                        'danger' => 'FAILED',
                        'purple' => 'REFUNDED',
                    ]),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'WAITING_PAYMENT' => 'Waiting Payment',
                        'PAID' => 'Paid',
                        'PROCESSING' => 'Processing',
                        'SUCCESS' => 'Success',
                        'FAILED' => 'Failed',
                        'REFUNDED' => 'Refunded',
                        'EXPIRED' => 'Expired',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('refund')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->canBeRefunded())
                    ->action(fn (Order $record) => \App\Jobs\RefundJob::dispatch($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderResource\Widgets\OrderStats::class,
        ];
    }
}