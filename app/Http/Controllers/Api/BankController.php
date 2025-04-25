<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return Bank::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        return Bank::create($validated);
    }

    public function show($id)
    {
        return Bank::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $bank->update($request->validate([
            'name' => 'string',
        ]));

        return $bank;
    }

    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
