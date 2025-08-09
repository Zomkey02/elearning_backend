<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /*show all lessons in a course*/
    public function index(Request $request)
    {
        $lessons = Lesson::all();
        return response()->json(['lessons' => $lessons], 200);
    }

    public function store(Request $request, $courseId)
    {
        if(!Gate::allows('manage-all')) {
            return response()->json(['message' => 'Not authorized'], 403);
        }
        // Make sure lesson has a course
        $course = \App\Models\Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Validation and creation logic
        $validated = $request->validate($this->rules());
        $thumbnailPath = $this->handleThumbnailUpload($request, $validated['title']);
        if ($thumbnailPath) {
            $validated['thumbnail'] = $thumbnailPath;
        }

        $validated['course_id'] = $courseId;

        $user = $request->user();
        $validated['author_id'] = $user->id;

        $lesson = Lesson::create($validated);

        return response()->json(['lesson' => $lesson], status: 201);
        
    }
    /* Show specific lesson */
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

    public function update(Request $request, $courseId, $lessonId)
    {
        if(!Gate::allows('manage-all')) {
            return response()->json(['message' => 'Not authorized'], 403);
        }
        // Make sure lesson has a course
        $course = \App\Models\Course::findOrFail($courseId);

        $lesson = Lesson::where('id', $lessonId)
                    ->where('course_id', $courseId)
                    ->firstOrFail();

        $validated = $request->validate($this->rules($lesson->id));

        $thumbnailPath = $this->handleThumbnailUpload($request, $validated['title']);
        if ($thumbnailPath) { 
            $validated['thumbnail'] = $thumbnailPath;
        }

        $lesson->update($validated);

        return response()->json(['message' => 'Update successful', 'lesson' => $lesson], 200);

    }


    public function delete(Request $request, $courseId, $lessonId)
    {
        if (!Gate::allows('manage-all')) {
        return response()->json(['message' => 'Not authorized'], 403);
        }
        // Make sure lesson has a course
        $course = \App\Models\Course::findOrFail($courseId);

        $lesson = Lesson::where('id', $lessonId)
                    ->where('course_id', $courseId)
                    ->firstOrFail();

        if ($lesson->thumbnail && Storage::disk('public')->exists(str_replace('storage/', '', $lesson->thumbnail))) 
            {Storage::disk('public')->delete(str_replace('storage/', '', $lesson->thumbnail));
        }

        $lesson->delete();
        
        return response()->json(['message' => 'Deleted successfully'], 200);


    }

    protected function rules($lessonId = null)
    {
        return [
            'title'         => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:lessons,slug' . ($lessonId ? ",$lessonId" : ''),
            'summary'       => 'nullable|string',
            'content'       => 'required|string',
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duration'      => 'nullable|integer|min:1',
            'level'         => 'required|in:beginner,intermediate,advanced',
            'status'        => 'required|in:draft,published',
            'layout_type'   => 'required|in:standard,video-focused,image-left,interactive',
        ];
    }
    private function handleThumbnailUpload(Request $request, string $title): ?string
    {
        if (!$request->hasFile('thumbnail')) {
            return null;
        }

        $image = $request->file('thumbnail');
        $safeTitle = Str::slug($title);
        $extension = $image->getClientOriginalExtension();
        $filePath = $image->storeAs('lessons', $safeTitle . '.' . $extension, 'public');

        return 'storage/' . $filePath;
    }

    
}
