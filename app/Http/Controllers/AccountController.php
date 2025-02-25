<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Actions\FetchAccount;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    
    public function index(Request $request)
    {
        $accounts = (new FetchAccount)->execute($request);

        if ($request->ajax()) {
            return view('components.accounts.table', ['entity' => $accounts])->render();
        }
        return view('backend.accounts.index', compact('accounts'));

    }


    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:50',
                'type' => 'required|numeric',
                'balance' => 'nullable|numeric|min:0'
            ]);

            DB::beginTransaction();

            $account = Account::create([
                'name' => $request->name,
                'type' => $request->type,
                'balance' => 0
            ]);

            DB::commit();

            return response()->json(['message' => 'Account created successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }


    public function update(Request $request, Account $account)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:50,' . $account->id,
                'type' => 'required|numeric',
                'balance' => 'nullable|numeric|min:0,' . $account->id
            ]);
    
    
            $account->update([
                'name' => $request->name,
                'type' => $request->type,
                'balance' => 0
            ]);
    
            return response()->json(['message' => 'Account updated successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }


    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->back()->with('success', 'Account deleted successfully!');
    }

    
}
