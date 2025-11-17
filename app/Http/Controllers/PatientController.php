<?php

namespace App\Http\Controllers;


use App\Models\Patient;
use Illuminate\Http\Request;


class PatientController extends Controller
{
public function index()
{
return Patient::orderBy('id', 'DESC')->limit(100)->get();
}


public function store(Request $request)
{
$data = $request->validate([
'name' => 'required',
'birth_date' => 'nullable|date',
'notes' => 'nullable|string'
]);


Patient::create($data);
return response()->json(['status' => 'created'], 201);
}


public function show($id)
{
$patient = Patient::find($id);
if (!$patient) return response()->json(['error' => 'not found'], 404);
return $patient;
}


public function update(Request $request, $id)
{
$patient = Patient::find($id);
if (!$patient) return response()->json(['error' => 'not found'], 404);


$data = $request->validate([
'name' => 'required',
'birth_date' => 'nullable|date',
'notes' => 'nullable|string'
]);


$patient->update($data);
return response()->json(['status' => 'updated']);
}


public function destroy($id)
{
$patient = Patient::find($id);
if (!$patient) return response()->json(['error' => 'not found'], 404);
$patient->delete();
return response()->json(['status' => 'deleted']);
}
}