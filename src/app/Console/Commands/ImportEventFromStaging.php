<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventImport;
use App\Models\Event;
use App\Models\Category;
use App\Models\Organizer;
use App\Models\Location;
use App\Models\Audience;

class ImportEventFromStaging extends Command
{
    protected $signature = 'event:import-from-staging';
    protected $description = 'Extract valid data from event_imports and insert into events table. Leave invalid data for correction.';

    public function handle(): int
    {
        $this->info("🔁 Memulai proses ETL dari tabel staging `event_imports`...");

        $imported = 0;
        $skipped = 0;

        $isValidDate = function ($date) {
            $d = date_parse($date);
            return checkdate($d['month'] ?? 0, $d['day'] ?? 0, $d['year'] ?? 0);
        };

        $isValidTime = function ($time) {
            return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time);
        };

        $statusList = ['draft', 'published', 'archived', 'pending'];

        $records = EventImport::all();

        foreach ($records as $record) {
            // Validasi minimum: title, start_date, start_time wajib valid
            if (
                empty($record->title) ||
                !$isValidDate($record->start_date) ||
                !$isValidTime($record->start_time)
            ) {
                $this->warn("⛔ Baris ID {$record->id} dilewati: title/start_date/start_time tidak valid.");
                $skipped++;
                continue;
            }

            // Insert ke tabel event
            Event::create([
                'title' => $record->title,
                'description' => $record->description ?? '-',
                'start_date' => $record->start_date,
                'start_time' => $record->start_time,
                'end_date' => $isValidDate($record->end_date) ? $record->end_date : $record->start_date,
                'end_time' => $isValidTime($record->end_time) ? $record->end_time : '00:00',
                'status' => in_array($record->status, $statusList) ? $record->status : 'draft',
                'category_id' => is_numeric($record->category_id) && Category::find($record->category_id) ? $record->category_id : 1,
                'organizer_id' => is_numeric($record->organizer_id) && Organizer::find($record->organizer_id) ? $record->organizer_id : null,
                'location_id' => is_numeric($record->location_id) && Location::find($record->location_id) ? $record->location_id : null,
                'audience_id' => is_numeric($record->audience_id) && Audience::find($record->audience_id) ? $record->audience_id : null,
            ]);

            $record->delete(); // Hapus hanya jika berhasil di-insert
            $imported++;
        }

        $this->info("✅ ETL selesai. $imported data berhasil dipindahkan ke tabel `events`.");
        if ($skipped > 0) {
            $this->warn("⚠️ $skipped baris dilewati karena data tidak valid dan tetap di `event_imports`.");
        }

        return Command::SUCCESS;
    }
}
