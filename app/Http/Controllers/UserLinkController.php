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

        $user_id = $request->user_id ?? null;
        $user    = Auth::user();

        if ($user->role == 1) {
            $links = (!$user_id) ? Link::all() : Link::where('user_id', $user_id)->get();
        } else {
            if ($user_id and $user_id != $user->id) {
                throw new AuthorizationException("You are not allowed to access to links of other users");
            }
            $links = Link::where('user_id', $user->id)->get()->makeHidden('id');
        }

        return response()->json($links, 200);
    }

    public function update(UpdateLinkRequest $request): JsonResponse
    {


        $user = Auth::user();

        $link = Link::where('short_code', $request->shortCode);



        if ($user->role != 1) {

            $link->where('user_id', $user->id);
        }

        $link=$link->first();

        if (!$link) {
            throw new NotFoundHttpException('Link not found.');
        }


        $newOriginalUrl = $request->input('original_url');
        $newShortCode = $request->input('new_short_code')??$link->short_code;


        $link->update(['original_url'=>$newOriginalUrl,'short_code'=>$newShortCode]);

        return response()->json(['message' => 'Link updated successfully'], 200);
    }
}

