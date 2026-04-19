<?php

namespace App\Observers;

use App\Models\Kemitraan;

class KemitraanObserver
{
    public function updated(Kemitraan $kemitraan): void
    {
        // Jika status berubah menjadi disetujui
        if ($kemitraan->statusPengajuan === 'disetujui') {
            $kemitraan->user->update([
                'isActive' => true
            ]);
        }

        // Jika ditolak, pastikan tetap tidak aktif
        if ($kemitraan->statusPengajuan === 'ditolak') {
            $kemitraan->user->update([
                'isActive' => false
            ]);
        }
    }
}