<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate($this->rules());
        $thumbnailPath = $this->handleThumbnailUpload($request, $validated['title']);
        if ($thumbnailPath) {
            $validated['thumbnail'] = $thumbnailPath;
        }

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
        return response()->json(['course' => $course], status: 200);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('manage-all')) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $course = Course::findOrFail($id);

        $validated = $request->validate($this->rules($course->id));
        $thumbnailPath = $this->handleThumbnailUpload($request, $validated['title']);

        if ($thumbnailPath) {

            if ($course->thumbnail && Storage::disk('public')->exists(str_replace('storage/','',$course->thumbnail))) {
                Storage::disk('public')->delete(str_replace('storage/','',$course->thumbnail));
            }

            $validated['thumbnail'] = $thumbnailPath;
        }

        $course->update($validated);

        return response()->json(['message' => 'Update successful', 'course' => $course], 200);
    }

    public function delete($id)
    {
        if (!Gate::allows('manage-all')) {
        return response()->json(['message' => 'Not authorized'], 403);
        }

        $course = Course::findOrFail($id);

        if ($course->thumbnail && Storage::disk('public')->exists(str_replace('storage/', '', $course->thumbnail))) 
            {Storage::disk('public')->delete(str_replace('storage/', '', $course->thumbnail));
        }
        $course->delete();

        return response()->json(['message' => 'Course and its lessons deleted successfully'], 200);
    }

    protected function rules($courseId = null)
    {
        return [
            'title'         => 'required|string|max:200',
            'slug'          => 'required|string|max:255|unique:courses,slug' . ($courseId ? ",$courseId" : ''),
            'summary'       => 'required|string',
            'description'   => 'required|string',
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duration'      => 'required|integer|min:1',
            'status'        => 'required|in:draft,published',
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
        $fileName = $safeTitle . '.' . $extension;
        $filePath = $image->storeAs('courses', $fileName, 'public');

        /*$filePath = $image->storeAs('courses', $safeTitle . '.' . time(). $extension, 'public' );*/

        return 'storage/' . $filePath;
    }
}
