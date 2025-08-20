<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonProgressController extends Controller
{
    public function markCompleted(Request $request, $courseId, $lessonId) {

        $lesson= Lesson::where('id', $lessonId)
            ->where('course_id', $courseId)
            ->first();
    
        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }

        if ($lesson->status !== 'published' && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Not authorized to comolete Lesson'], 403);
        }

        $progress = LessonProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'lesson_id' => $lesson->id],
            ['completed' => true]
        );

        return response()->json([
            'message' => 'Lesson completed successfully',
            'progress' => [
                'lesson_id' => (int) $lesson->id,
                'completed' => (bool) $progress->completed,
            ]
            ], 200);
    }

    public function getLessonProgress(Request $request, $courseId, $lessonId) {
        $progress = LessonProgress::where('user_id', $request->user()->id)
            ->where('lesson_id', $lessonId)
            ->first();

        return response()->json([
            'completed' => $progress ? (bool)$progress->completed : false
        ]);
    }

    public function getCourseProgress(Request $request, $courseId) {

        $total = Lesson::where('course_id', $courseId)
            ->where('status', 'published')
            ->count();
        
        $completed = LessonProgress::where('user_id', $request->user()->id)
            ->where('completed', true)
            ->whereHas('lesson', function ($q) use ($courseId) {
                $q->where('course_id', $courseId)
                  ->where('status', 'published');
            })
            ->count();
        
        return response()->json([
            'completedLessons' => $completed,
            'totalLessons' => $total,
        ], 200);
    }

    public function getAllCourseProgress(Request $request) {

        $userId = $request->user()->id;

        $progressEntries = LessonProgress::with('lesson.course')
            ->where('user_id', $userId)
            ->get();
        
        $grouped = $progressEntries->groupBy(fn($p) => $p->lesson->course_id);

        $result = [];

        foreach ($grouped as $courseId => $lessons) {
            $total = Lesson::where('course_id', $courseId)
                ->where('status', 'published')
                ->count();
            
            $completed = $lessons->where('completed', true)->count();
            $courseTitle = $lessons->first()->lesson->course->title ?? 'Unknown';

            $result[] = [
                'courseId' => $courseId,
                'courseTitle' => $courseTitle,
                'completedLessons' => $completed,
                'totalLessons' => $total,
            ];
        }
        return response()->json($result, 200);
    }
}
