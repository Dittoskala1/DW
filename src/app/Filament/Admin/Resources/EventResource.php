<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages;
use App\Models\Event;
use App\Models\Category;
use App\Models\Organizer;
use App\Models\Location;
use App\Models\Audience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Events';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\DatePicker::make('start_date')
                    ->required(),

                Forms\Components\TimePicker::make('start_time')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->required(),

                Forms\Components\TimePicker::make('end_time')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                        'pending' => 'Pending',
                    ]),

                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->relationship('category', 'name'),

                Forms\Components\Select::make('organizer_id')
                    ->label('Organizer')
                    ->relationship('organizer', 'name')
                    ->nullable(),

                Forms\Components\Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'venue_name')
                    ->nullable(),

                Forms\Components\Select::make('audience_id')
                    ->label('Audience')
                    ->relationship('audience', 'name')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('start_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('organizer.name')->label('Organizer')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('location.venue_name')->label('Location')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('audience.name')->label('Audience')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Action::make('Import CSV')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('Upload CSV')
                            ->disk('local') // simpan di storage/app
                            ->directory('uploads/csv')
                            ->required()
                            ->acceptedFileTypes(['text/csv', 'text/plain']),
                    ])
                    ->action(function (array $data): void {
                        try {
                            $filePath = Storage::disk('local')->path($data['csv_file']);
                            $csv = \League\Csv\Reader::createFromPath($filePath, 'r');
                            $csv->setHeaderOffset(0);

                            // Validasi header kolom
                            $expectedHeaders = [
                                'title',
                                'description',
                                'start_date',
                                'start_time',
                                'end_date',
                                'end_time',
                                'status',
                                'category_id',
                                'organizer_id',
                                'location_id',
                                'audience_id'
                            ];
                            $headers = $csv->getHeader();
                            $missingHeaders = array_diff($expectedHeaders, $headers);
                            if (!empty($missingHeaders)) {
                                Notification::make()
                                    ->title('Kolom hilang: ' . implode(', ', $missingHeaders))
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Fungsi validasi
                            function isValidDate($date): bool
                            {
                                $d = date_parse($date);
                                return checkdate($d['month'] ?? 0, $d['day'] ?? 0, $d['year'] ?? 0);
                            }

                            function isValidTime($time): bool
                            {
                                return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time);
                            }

                            $successCount = 0;

                            foreach ($csv->getRecords() as $record) {
                                $categoryId = is_numeric($record['category_id']) && \App\Models\Category::find($record['category_id']) ? $record['category_id'] : 1;
                                $organizerId = is_numeric($record['organizer_id']) && \App\Models\Organizer::find($record['organizer_id']) ? $record['organizer_id'] : null;
                                $locationId = is_numeric($record['location_id']) && \App\Models\Location::find($record['location_id']) ? $record['location_id'] : null;
                                $audienceId = is_numeric($record['audience_id']) && \App\Models\Audience::find($record['audience_id']) ? $record['audience_id'] : null;

                                $statusList = ['draft', 'published', 'archived', 'pending'];
                                $status = in_array($record['status'], $statusList) ? $record['status'] : 'draft';

                                \App\Models\Event::create([
                                    'title' => $record['title'] ?? 'Untitled',
                                    'description' => $record['description'] ?? '-',
                                    'start_date' => isValidDate($record['start_date']) ? $record['start_date'] : '2222-01-01',
                                    'start_time' => isValidTime($record['start_time']) ? $record['start_time'] : '00:00',
                                    'end_date' => isValidDate($record['end_date']) ? $record['end_date'] : '2222-01-01',
                                    'end_time' => isValidTime($record['end_time']) ? $record['end_time'] : '00:00',
                                    'status' => $status,
                                    'category_id' => $categoryId,
                                    'organizer_id' => $organizerId,
                                    'location_id' => $locationId,
                                    'audience_id' => $audienceId,
                                ]);

                                $successCount++;
                            }

                            Notification::make()
                                ->title("Import sukses. $successCount data berhasil diimport. Data tidak valid diisi default.")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Import gagal: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalHeading('Import Event dari CSV')
                    ->modalSubmitActionLabel('Import')
                    ->color('success')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
