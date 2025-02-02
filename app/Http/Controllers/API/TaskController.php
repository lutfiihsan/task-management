<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $task = Task::with('user', 'assignTo')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'message' => 'Tasks retrieved successfully',
            'tasks' => $task,
        ], 200);
    }

    public function store(CreateTaskRequest $request)
    {
        DB::beginTransaction();

        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'user_id' => auth()->user()->id,
                'status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Task created successfully',
                'task' => $task,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Task creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Task $task)
    {
        return response()->json([
            'message' => 'Task retrieved successfully',
            'task' => $task,
        ], 200);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        DB::beginTransaction();

        try {
            //validasi hanya pemilik task yg dapat update
            if ($task->user_id !== auth()->user()->id) {
                return response()->json([
                    'message' => 'You are not authorized to update this task',
                ], 403);
            }

            //validasi user tidak dapat ubah status pending ke completed tanpa melewati p
            if ($task->status === 'pending' && $request->status === 'completed') {
                return response()->json([
                    'message' => 'You are not authorized to update this task',
                ], 403);
            }

            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'status' => $request->status,
            ]);

            //simpan riwayat perubahan kedalam TaskHistory
            $task->history()->create([
                'task_id' => $task->id,
                'data' => auth()->user()->name . " telah mengubah status dari " . $task->status . " menjadi " . $request->status,
                'user_id' => auth()->user()->id
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Task updated successfully',
                'task' => $task,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Task update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        DB::beginTransaction();

        try {
            //validasi hanya pemilik task yg dapat delete
            if ($task->user_id !== auth()->user()->id) {
                return response()->json([
                    'message' => 'You are not authorized to delete this task',
                ], 403);
            }

            $task->delete();

            DB::commit();

            return response()->json([
                'message' => 'Task deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Task deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function assign(Request $request, Task $task)
    {
        DB::beginTransaction();

        try {
            //validasi hanya user lain yg dapat ditugaskan
            if (intval($request->assign_to) === auth()->user()->id) {
                return response()->json([
                    'message' => 'Task cannot be assigned to yourself',
                ], 403);
            }

            $task->update([
                'assign_to' => $request->assign_to,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Task assigned successfully',
                'task' => $task,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Task assignment failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function notification(Task $task)
    {
        //user yg di assign task akan mendapatkan notifikasi berupa api
        return response()->json([
            'message' => 'Notification sent successfully',
            'task' => $task->with(['user', 'assignTo'])->where('assign_to', auth()->user()->id)->latest()->first() ?? null,
        ]);
    }

    public function history(Task $task)
    {
        return response()->json([
            'message' => 'Task history retrieved successfully',
            'history' => $task->history,
        ], 200);
    }
}
