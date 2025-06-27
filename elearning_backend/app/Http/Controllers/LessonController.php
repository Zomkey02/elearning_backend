<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    
    public function store(Request $request)
    {
        // Validation and creation logic
        $validated = $request->validate([
            'course_id'     => 'required|exists:courses,id',
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

        $user = $request->user();

        $validated['author_id'] = $user->id;

        $lesson = Lesson::create($validated);

        return response()->json(['lesson' => $lesson], status: 201);
        
    }

    public function show(string $id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], status: 404);
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
