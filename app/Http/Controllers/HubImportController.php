<?php

namespace App\Http\Controllers;

use App\Imports\HubsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class HubImportController extends Controller
{
    public function import(Request $request)
    {

        try {
            $request->validate([
                'file' => 'required|mimes:xls,xlsx',
            ]);

            $filePath = $request->file('file')->store('imports', 'public');
            $path = Storage::disk('public')->path($filePath);
            Excel::import(new HubsImport, $path);
            Storage::delete($filePath);

            return response()->json(['message' => 'Hubs imported successfully'], 200);
        } catch (ValidationException $v) {

            return response()->json(['error' => $v->validator->errors()->first()], 422);
        } catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {

            return response()->json(['error' => 'File type not supported'], 400);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
