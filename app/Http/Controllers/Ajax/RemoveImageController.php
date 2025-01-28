<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Throwable;

class RemoveImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Image $image)
    {
        try {

            $image->deleteOrFail();
            return response()->json([
                'message' => 'Image removed successfully.',
            ]);

        } catch (Throwable $th) {
            logs()->error("[RemoveImageController] Failed to remove image from database {$th->getMessage()}", [
                    'image' => $image->id,
                    'exception' => $th
                ]
            );
            return response()->json([
                'message' => $th->getMessage(),
            ], 422);
        }

    }
}
