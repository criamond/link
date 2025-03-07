<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class ShortURLController extends Controller
{
    public function store(StoreLinkRequest $request): JsonResponse
    {

        $user_id = Auth::id() ?? null;

        $shortCode = $request->short_code ?? Str::random(6);

        try {

            $link = Link::create([
                'original_url' => $request->original_url,
                'short_code'   => $shortCode,
                'user_id'      => $user_id,
            ]);
        } catch (Exception $exception) {
            throw ValidationException::withMessages([
                'short_code' => ['already used'],
            ]);
        }

        return response()->json([
            'short_code'   => $link->short_code,
            'original_url' => $link->original_url,
        ], 201);
    }

    public function redirect($shortCode)
    {

        $link = Link::where('short_code', $shortCode)->firstOrFail();

        $link->increment('click_count');

        return redirect($link->original_url);
    }
}
