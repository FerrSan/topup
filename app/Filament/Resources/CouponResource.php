<?php

// app/Filament/Resources/CouponResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationGroup = 'Marketing';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Coupon Details')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        Forms\Components\Select::make('type')
                            ->options([
                                'fixed' => 'Fixed Amount',
                                'percent' => 'Percentage',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->prefix(fn (callable $get) => $get('type') === 'percent' ? '%' : 'Rp'),
                        Forms\Components\TextInput::make('min_spend')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Minimum order amount to use this coupon'),
                        Forms\Components\TextInput::make('max_discount')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Maximum discount amount (for percentage type)'),
                        Forms\Components\TextInput::make('usage_limit')
                            ->numeric()
                            ->helperText('Total number of times this coupon can be used'),
                        Forms\Components\TextInput::make('user_limit')
                            ->numeric()
                            ->default(1)
                            ->helperText('Number of times a single user can use this coupon'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Validity Period')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_at'),
                        Forms\Components\DateTimePicker::make('end_at'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
                
                Forms\Components\Section::make('Restrictions')
                    ->schema([
                        Forms\Components\TagsInput::make('applicable_games')
                            ->helperText('Leave empty to apply to all games'),
                        Forms\Components\TagsInput::make('applicable_products')
                            ->helperText('Leave empty to apply to all products'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'fixed',
                        'info' => 'percent',
                    ]),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn (string $state, $record) => 
                        $record->type === 'percent' ? $state . '%' : 'Rp ' . number_format($state, 0, ',', '.')
                    ),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Used')
                    ->formatStateUsing(fn (string $state, $record) => 
                        $state . '/' . ($record->usage_limit ?? '∞')
                    ),
                Tables\Columns\TextColumn::make('min_spend')
                    ->money('IDR'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percent' => 'Percentage',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}