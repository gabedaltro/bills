<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['user', 'bank']);

        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if ($request->has('initial_date') && $request->has('final_date')) {
            $query->whereBetween('due_date', [$request->initial_date, $request->final_date]);
        }

        // 🔀 Ordenação (ex: ?order_by=amount&direction=desc)
        if ($request->has('order_by')) {
            $direction = $request->get('direction', 'asc');
            $query->orderBy($request->order_by, $direction);
        }

        // 📄 Paginação (padrão: 10 por página)
        $bills = $query->paginate($request->get('per_page', 10));

        return response()->json($bills);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'bank_id' => 'required|exists:banks,id',
                'amount' => 'required|numeric',
                'installments' => 'required|integer|min:1',
                'current_installment' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
            ]);

            $bill = Bill::create($validated);

            return response()->json($bill, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar fatura',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        $bill = Bill::with(['user', 'bank'])->findOrFail($id);
        return response()->json($bill);
    }

    public function update(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'bank_id' => 'sometimes|exists:banks,id',
            'amount' => 'sometimes|numeric',
            'installments' => 'sometimes|integer|min:1',
            'current_installment' => 'sometimes|integer|min:1',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
        ]);

        $bill->update($validated);

        return response()->json($bill);
    }

    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();

        return response()->json(null, 204);
    }

    public function user($id)
    {
        $bill = Bill::with('user')->findOrFail($id);
        return response()->json([
            'bill' => $bill->only(['id', 'value', 'installments', 'current_installment', 'bank']),
            'user' => $bill->user,
        ]);
    }
}
