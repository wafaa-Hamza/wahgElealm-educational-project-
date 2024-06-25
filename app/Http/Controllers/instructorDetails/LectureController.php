<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\CoursesOfInstructor;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllLectures = Lecture::all();
        return $AllLectures;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'duration' => 'required|string',
            'is_completed' => 'required|boolean',
            'video' => 'sometimes|file|mimes:mp4,mov,avi,flv|max:20480',

        ]);
        $filename = null;
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = storage_path('app/private/videos/' . $filename);

            // تشفير الفيديو قبل تخزينه
            $videoContent = file_get_contents($file->getRealPath());
            $encryptedContent = openssl_encrypt($videoContent, 'AES-256-CBC', env('ENCRYPTION_KEY'), 0, '1234567890123456');

            file_put_contents($path, $encryptedContent);
        }

        $data = $request->all();
        $data['video'] = $filename;

       $LectureStore=  Lecture::create($data);

         $LectureStore->save();

            return response()->json([
                'message' => 'Lecture Created Successfully',
                'data'    =>  $LectureStore ,

            ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lecture = Lecture::findOrFail($id);

    return response()->json([
        'status' => true,
        'lecture' => [
            'title' => $lecture->title,
            'duration' => $lecture->duration,
            'is_completed' => $lecture->is_completed,
            'video' => $lecture->video, // عرض الفيديو
        ],
    ]);
    }

    public function showVideo($id)
    {
        $Lecture = Lecture::findOrFail($id);

        if (!$Lecture->video) {
            abort(404, 'Video not found');
        }

        $path = storage_path('app/private/videos/' . $Lecture->video);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Lecture file not found'], 404);
        }

        $encryptedContent = file_get_contents($path);
        $videoContent = openssl_decrypt($encryptedContent, 'AES-256-CBC', env('ENCRYPTION_KEY'), 0, '1234567890123456');

        return response($videoContent)->header('Content-Type', 'video/mp4');

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'string',
            'duration' => 'string',
            'is_completed' => 'boolean',
            'video' => 'sometimes|file|mimes:mp4,mov,avi,flv|max:20480',

        ]);

        $lectureUpdate=  Lecture::findOrFail($id);

        if ($lectureUpdate->video) {
          $oldVideoPath = storage_path('app/private/videos/' . $lectureUpdate->video);
          if (file_exists($oldVideoPath)) {
              unlink($oldVideoPath);
          }
      }
      if ($request->hasFile('video')) {
          $file = $request->file('video');
          $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
          $path = storage_path('app/private/videos/' . $filename);

          // تشفير الفيديو قبل تخزينه
          $videoContent = file_get_contents($file->getRealPath());
          $encryptedContent = openssl_encrypt($videoContent, 'AES-256-CBC', env('ENCRYPTION_KEY'), 0, '1234567890123456');

          file_put_contents($path, $encryptedContent);

          $data['video'] = $filename;

      }
      $lectureUpdate->update($data);

      return response()->json([
          'message' => 'Product updated successfully',
          'data'    => $lectureUpdate,
      ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lecture = Lecture::findOrFail($id);
        $lecture->delete();
        return response()->json(null, 204);
    }
    }

