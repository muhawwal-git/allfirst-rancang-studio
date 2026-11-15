<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PreviewVideoController extends Controller
{
    /**
     * Stream a free preview lesson. Range requests (HTTP 206) are handled by BinaryFileResponse,
     * while the 15-second limit is enforced by the player on the client side.
     */
    public function __invoke(Materi $materi): BinaryFileResponse
    {
        abort_unless($materi->cuplikan_gratis, 403);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($materi->path_video), 404);

        return response()->file($disk->path($materi->path_video), [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, max-age=0, no-store',
        ]);
    }
}
