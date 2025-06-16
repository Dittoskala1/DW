<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventRegistrationResource\Pages;
use App\Models\EventRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;
    public static function getNavigationGroup(): ?string
    {
        return 'Events';
    }

    protected static ?int $navigationSort = -2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('applicant_name')
                ->label('Applicant Name')
                ->required(),

            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),

            // Date and Time pickers
            Forms\Components\DatePicker::make('start_date')
                ->required(),

            Forms\Components\TimePicker::make('start_time') 
                ->required(),

            Forms\Components\DatePicker::make('end_date')
                ->required(),

            Forms\Components\TimePicker::make('end_time') 
                ->required(),

            // Category
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->nullable(),

            // Location
            Forms\Components\Select::make('location_id')
                ->label('Location')
                ->relationship('location', 'venue_name')
                ->nullable(),

            // Audience
            Forms\Components\Select::make('audience_id')
                ->label('Audience')
                ->relationship('audience', 'name')
                ->nullable(),

            // Status
            Forms\Components\Select::make('status')
                ->label('Status')
                ->required()
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->default('pending'),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicant_name')
                    ->label('Applicant Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time'),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time'),

                
                Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->sortable(),
                
                Tables\Columns\TextColumn::make('location.venue_name')
                ->label('Location')
                ->sortable(),
                
                Tables\Columns\TextColumn::make('audience.name')
                ->label('Audience')
                ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListEventRegistrations::route('/'),
            'create' => Pages\CreateEventRegistration::route('/create'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }
}
