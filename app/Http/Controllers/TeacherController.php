<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return $teachers;
    }
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name'     => 'required|max:254',
                'description' => 'required|max:254',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
            }


            $teacherStore = Teacher::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'image'=>$request->image
            ]);
            if (isset($filename)) {
                $teacherStore->image = $filename;
            }
            $teacherStore->save();


            return response()->json([
                'message' => 'TeacherProfile Added Successfully',
                'data'    =>  $teacherStore ,
            ], 201);

        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    => $e->errors(),
            ], 400);
        }
    }

    public function show(string $id)
    {
        $teacherShow=Teacher::findOrFail($id);
        return  $teacherShow;

    }

    public function update(Request $request,$id)
    {
            $request->validate([
                'name'     => 'max:254',
                'description' => 'max:254',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $teacherData = Teacher::find($id);

            if (!$teacherData) {
                return response()->json(['error' => 'Teacher not found'], 404);
            }

            if ($request->hasFile('image')) {
                Storage::delete('public/images/' . $teacherData->image);

                $image     = $request->file('image');
                $filename  = 'image' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
                $teacherData->image = $filename;
            }

            $teacherData->name        = $request->input('name', $teacherData->name);
            $teacherData->description = $request->input('description', $teacherData->description);
            $teacherData->save();


            return response(['data' =>  'TeacherProfile Updated successfully' ], 200);
    }


    public function destroy(string $id)
    {
                $InsDataDestroy=Teacher::where('id',$id)->delete();
              return response()->json([
            'status'     => true,
            'message'    => 'TeacherProfile Deleted Successfully',
        ],201);
    }
}


