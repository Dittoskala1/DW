<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\EventRegistration;

class EventRegistrationObserver
{
    public function updated(EventRegistration $registration): void
    {
        if ($registration->isDirty('status')) {
            if ($registration->status === 'approved') {
                // Cek apakah sudah ada event sebelumnya
                $existingEvent = Event::where([
                    ['title', $registration->title],
                    ['start_date', $registration->start_date],
                    ['start_time', $registration->start_time],
                ])->first();

                // Hanya buat jika belum ada
                if (!$existingEvent) {
                    Event::create([
                        'title'        => $registration->title,
                        'description'  => $registration->description,
                        'start_date'   => $registration->start_date,
                        'start_time'   => $registration->start_time,
                        'end_date'     => $registration->end_date,
                        'end_time'     => $registration->end_time,
                        'status'       => 'draft',
                        'category_id'  => $registration->category_id,
                        'location_id'  => $registration->location_id,
                        'audience_id'  => $registration->audience_id,
                        'organizer_id' => null,
                    ]);
                }

            } elseif ($registration->status === 'rejected') {
                // Hapus dari tabel event jika sebelumnya pernah dibuat
                Event::where([
                    ['title', $registration->title],
                    ['start_date', $registration->start_date],
                    ['start_time', $registration->start_time],
                ])->delete();
            }
        }
    }
}
