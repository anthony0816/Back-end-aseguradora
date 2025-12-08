<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // Limita los campos del owner a 'id' y 'name'
        return response()->json(
            Account::with(['owner' => function ($query) {
                $query->select('id', 'name'); 
            }])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:user,id',
            'login' => 'required|numeric|unique:account',
            'trading_status' => 'required|string|in:enable,disable',
            'status' => 'required|string|in:enable,disable',
        ]);
        
        $account = Account::create($validated);

        // Devuelve el objeto creado con el owner limitado
        return response()->json($account->load(['owner' => function ($query) {
            $query->select('id', 'name'); 
        }]), 201);
    }

    public function show(Account $account)
    {
        // Limita los campos del owner a 'id' y 'name'
        return response()->json(
            $account->load(['owner' => function ($query) {
                $query->select('id', 'name'); 
            }])
        );
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            // El login debe ser único, excluyendo el account actual
            'login' => 'sometimes|numeric|unique:account,login,' . $account->id,
            'trading_status' => 'sometimes|string|in:enable,disable',
            'status' => 'sometimes|string|in:enable,disable',
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json(null, 204);
    }
}