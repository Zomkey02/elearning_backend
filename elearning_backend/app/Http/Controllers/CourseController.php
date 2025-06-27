<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json(['courses' => $courses], 200);
    }

    public function store (Request $request)
    {
        if(!Gate::allows('manage-all')) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        // Validation and creation logic
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:lessons',
            'summary'       => 'nullable|string',
            'description'       => 'required|string',
            'thumbnail_url' => 'nullable|url',
            'duration'      => 'nullable|integer|min:1',
            'status'        => 'required|in:draft,published',
        ]);

        $user = $request->user();

        $validated['author_id'] = $user->id;

        $course = Course::create($validated);
        

        return response()->json(['course' => $course], status: 201);
    }

    public function show($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], status: 404);
        }
        return response()->json(['lesson' => $course], status: 200);
    }

    public function update(Request $request, $id)
    {
        
    }
}
