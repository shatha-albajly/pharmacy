<?php

namespace App\Http\Controllers\neighborhood;
use App\Models\Neighborhood;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class NeighborhoodController extends Controller
{

    public function index()
    {
      $neighborhoods = Neighborhood::get();
      return response($neighborhoods);
      // return view('neighborhoods', compact('neighborhoods'));
    }

    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'name' => 'required|min:2|max:30|string',
        'directorate_id' => 'required'
      ]);
      Neighborhood::create([
        'name' => $request->input('name'),
        'directorate_id' => $request->input('directorate_id'),
      ]);

      return response(['added successfully', $validator->errors()]);
    //  return redirect()->back()->with('status', 'added successfully');
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
