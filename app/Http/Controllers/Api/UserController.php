<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $perPage = $request->get('per_page', 10);
        return response()->json($query->paginate($perPage));
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'pix_key' => 'required|array',
                'pix_key.*' => 'string',
            ]);

            $user = User::create($validated);

            return response()->json($user, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validação falhou',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar usuário',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'pix_key' => 'sometimes|string',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }

    public function bills($id, Request $request)
    {
        $user = User::findOrFail($id);
        $perPage = $request->get('per_page', 10);

        $bills = $user->bills()->paginate($perPage);

        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'pix_key']),
            'bills' => $bills,
        ]);
    }

}
