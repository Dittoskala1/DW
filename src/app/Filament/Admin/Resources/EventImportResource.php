<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventImportResource\Pages;
use App\Filament\Admin\Resources\EventImportResource\RelationManagers;
use App\Models\EventImport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Illuminate\Support\Str;

class EventImportResource extends Resource
{
    protected static ?string $model = EventImport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('start_date')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('start_time')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('end_date')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('end_time')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('category_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('organizer_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('location_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('audience_id')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('audience_id')
                    ->numeric()
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

            ->headerActions([
                \Filament\Tables\Actions\Action::make('Import CSV')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('Upload CSV')
                            ->disk('local')
                            ->directory('uploads/csv')
                            ->required()
                            ->acceptedFileTypes(['text/csv', 'text/plain']),
                    ])
                    ->action(function (array $data): void {
                        try {
                            $filePath = Storage::disk('local')->path($data['csv_file']);
                            $csv = \League\Csv\Reader::createFromPath($filePath, 'r');
                            $csv->setHeaderOffset(0);

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

                            // Helper functions
                            function isValidDate($date): bool
                            {
                                $d = date_parse($date);
                                return checkdate($d['month'] ?? 0, $d['day'] ?? 0, $d['year'] ?? 0);
                            }

                            function isValidTime($time): bool
                            {
                                return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time);
                            }

                            function cleanText(?string $text): ?string
                            {
                                $text = trim(preg_replace('/\s+/', ' ', $text));
                                $text = preg_replace('/[^\x20-\x7E]/', '', $text); // Bersihkan karakter aneh
                                return in_array(strtolower($text), ['n/a', '-', 'null', '']) ? null : $text;
                            }

                            $successCount = 0;

                            foreach ($csv->getRecords() as $index => $record) {
                                $rowNumber = $index + 2; // Header di baris 1
            
                                $title = \Illuminate\Support\Str::limit(
                                    ucfirst(strtolower(cleanText($record['title'] ?? ''))),
                                    255
                                );
                                $title = $title !== '' ? $title : null;

                                $description = cleanText($record['description'] ?? null);
                                $startDate = isValidDate($record['start_date']) ? $record['start_date'] : null;
                                $startTime = isValidTime($record['start_time']) ? $record['start_time'] : null;
                                $endDate = isValidDate($record['end_date']) ? $record['end_date'] : null;
                                $endTime = isValidTime($record['end_time']) ? $record['end_time'] : null;

                                $statusList = ['draft', 'published', 'archived', 'pending'];
                                $status = strtolower(trim($record['status'] ?? ''));
                                $status = in_array($status, $statusList) ? $status : null;

                                $categoryId = is_numeric($record['category_id']) ? (int) $record['category_id'] : null;
                                $organizerId = is_numeric($record['organizer_id']) ? (int) $record['organizer_id'] : null;
                                $locationId = is_numeric($record['location_id']) ? (int) $record['location_id'] : null;
                                $audienceId = is_numeric($record['audience_id']) ? (int) $record['audience_id'] : null;

                                // Simpan ke tabel sementara (event_imports) meskipun ada data yang null
                                \App\Models\EventImport::create([
                                    'title' => $title,
                                    'description' => $description,
                                    'start_date' => $startDate,
                                    'start_time' => $startTime,
                                    'end_date' => $endDate,
                                    'end_time' => $endTime,
                                    'status' => $status,
                                    'category_id' => $categoryId,
                                    'organizer_id' => $organizerId,
                                    'location_id' => $locationId,
                                    'audience_id' => $audienceId,
                                ]);

                                $successCount++;
                            }

                            Notification::make()
                                ->title("Import selesai: $successCount baris berhasil dimuat ke `event_imports`.")
                                ->success()
                                ->send();

                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Import gagal: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalHeading('Import Event ke Tabel Sementara (event_imports)')
                    ->modalSubmitActionLabel('Import')
                    ->color('success'),
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
            'index' => Pages\ListEventImports::route('/'),
            'create' => Pages\CreateEventImport::route('/create'),
            'edit' => Pages\EditEventImport::route('/{record}/edit'),
        ];
    }
}
