<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetAllLinksRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UserLinkController extends Controller
{

    public function getAllLinks(GetAllLinksRequest $request): JsonResponse
    {

        $user_id = $user_id ?? null;
        $user    = Auth::user();

        if ($user->role == 1) {
            $links = ($user_id) ? Link::all() : Link::where('user_id', $user_id)->get();
        } else {
            if ($user_id and $user_id != $user->id) {
                throw new AuthorizationException("You are not allowed to access to links of other users");
            }
            $links = Link::where('user_id', $user->id)->get()->makeHidden('id');
        }

        return response()->json($links, 200);
    }

    public function update(UpdateLinkRequest $request, $shortCode): JsonResponse
    {
        $validated = $request->validate([
            'original_url' => 'required|url',
            'short_code'   => 'nullable|string|unique',
        ]);

        $user = Auth::user();

        $link = Link::where('short_code', $shortCode);

        if ($user->role != 1) {

            $link->where('user_id', $user->id);
        }

        $link->first();

        if (!$link) {
            throw new NotFoundHttpException('Link not found.');
        }


        $link->update($validated);

        return response()->json(['message' => 'Link updated successfully'], 200);
    }
}

