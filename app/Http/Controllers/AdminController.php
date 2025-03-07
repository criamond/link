<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DestroyUserRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{

    public function destroy(int $id): JsonResponse
    {
        $link = Link::find($id);

        if (!$link) {
            throw new NotFoundHttpException('Link not found.');
        }

        $link->delete();

        return response()->json(['message' => 'Link deleted successfully'], 200);
    }


    public function listUsers(): JsonResponse
    {
        $users = User::all();

        return response()->json($users);
    }

    public function deleteUser(DestroyUserRequest $request): JsonResponse
    {

        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

}
