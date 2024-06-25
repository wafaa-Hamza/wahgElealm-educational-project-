<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\Correct_answer;
use App\Models\instructor;
use App\Models\Test;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructors = instructor::all();
        return $instructors;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name'     => 'required|max:254',
                'email'     => 'required||max:254',
                'description' => 'required|max:254',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
            }


            $instructirsStore = instructor::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'description'=>$request->description,
                'image'=>$request->image
            ]);
            if (isset($filename)) {
                $instructirsStore->image = $filename;
            }
            $instructirsStore->save();


            return response()->json([
                'message' => 'instr Added Successfully',
                'data'    =>  $instructirsStore ,
            ], 201);

        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    => $e->errors(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instShow=instructor::findOrFail($id);
        return  $instShow;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
            $request->validate([
                'name'     => 'max:254',
                'email'     => 'max:254',
                'description' => 'max:254',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);



            $instructorData = instructor::find($id);


            if (!$instructorData) {
                return response()->json(['error' => 'instructor not found'], 404);
            }
            if ($request->hasFile('image')) {
                Storage::delete('public/images/' . $instructorData->image);

                $image     = $request->file('image');
                $filename  = 'image' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
                $instructorData->image = $filename;
            }

            $instructorData->name        = $request->input('name', $instructorData->name);
            $instructorData->email        = $request->input('email', $instructorData->email);
            $instructorData->description = $request->input('description', $instructorData->description);
            $instructorData->save();



            return response(['data' =>  ' Instructot_Profile Updated successfully' ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
                $InsDataDestroy=instructor::where('id',$id)->delete();
              return response()->json([
            'status'     => true,
            'message'    => 'Instructot_Profile Deleted Successfully',
        ],201);
    }

/////////////////////////////// لمدرس يصحح الدرجات ديناميك ///////////////
    public function showTests()
    {
        $tests = Test::with('questions.correctAnswers.student')->get();
        return $tests;
    }

    public function correctAnswer(Request $request)
    {

        $answerid=$request->answerid;
        $request->validate([
            'is_correct' => 'required|boolean',
        ]);

        $answer = Correct_answer::findOrFail($answerid);
        $answer->is_correct = $request->is_correct;
        $answer->save();

        return response(['data' =>  ' Answer corrected successfully.' ], 200);
    }
}

