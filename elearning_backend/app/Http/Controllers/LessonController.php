<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $lessons = Lesson::all();
        return response()->json(['courses' => $lessons], 200);
    }

    
    public function store(Request $request, $courseId)
    {
        if(!Gate::allows('manage-all')) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        // Validation and creation logic
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:lessons',
            'summary'       => 'nullable|string',
            'content'       => 'required|string',
            'thumbnail_url' => 'nullable|url',
            'duration'      => 'nullable|integer|min:1',
            'level'         => 'required|in:beginner,intermediate,advanced',
            'status'        => 'required|in:draft,published',
            'layout_type'   => 'required|in:standard,video-focused,image-left,interactive',
        ]);

        $validated['course_id'] = $courseId;

        $user = $request->user();
        $validated['author_id'] = $user->id;

        $lesson = Lesson::create($validated);

        return response()->json(['lesson' => $lesson], status: 201);
        
    }

    public function show(Request $request, $courseId, $lessonId)
    {
        //$lesson = Lesson::find($id);
        $lesson = Lesson::where('id', $lessonId)->where('course_id', $courseId)->first();

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], status: 404);
        }
        
        if ($lesson->status !== 'published' && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Not authorized to view this lesson'], 403);
        }
        
        return response()->json(['lesson' => $lesson], status: 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    /* public function edit(string $id)
    {
        //
    } */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
