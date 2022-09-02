<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * implements transactional databases on every action
 */

class TaskController extends Controller
{
    public function view()
    {
        $task = Task::get();
        return response($task);
    }

    public function create(Request $req)
    {
        $task = $req->task;

        DB::beginTransaction();
        $create=Task::insert([
            'tasks' => $task,
            'status' => 1, // 1 = active task, 0 = task done
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        if ($create == TRUE) {
            $respo['success'] = true;
            DB::commit(); // commit if no query and input mistakes
        } else {
            $respo['success'] = false;
            DB::rollBack(); // rollback if has mistakes
        }

        return response($respo);
    }

    public function update(Request $req)
    {
        $id = $req->segment(4);
        $task = $req->task;

        DB::beginTransaction();
        $create=Task::where('id', $id)->update([
            'tasks' => $task,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        if ($create == TRUE) {
            $respo['success'] = true;
            DB::commit();
        } else {
            $respo['success'] = false;
            DB::rollBack();
        }

        return response($respo);        
    }

    public function update2(Request $req)
    {
        $id = $req->segment(4);
        $status = $req->status == true ? 0 : 1; // baliktad, kasi pag tapos na yung task, dapat zero na yung status which is true yung checkbox

        DB::beginTransaction();
        $create=Task::where('id', $id)->update([
            'status' => $status,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        if ($create == TRUE) {
            $respo['success'] = true;
            DB::commit();
        } else {
            $respo['success'] = false;
            DB::rollBack();
        }

        return response($respo);        
    }

    public function destroy(Request $req)
    {
        $id = $req->segment(4);

        DB::beginTransaction();
        $delete_task = Task::where('id', $id)->delete();

        if ($delete_task == TRUE) {
            $respo['success'] = true;
            DB::commit();
        } else {
            $respo['success'] = false;
            DB::rollBack();
        }

        return response($respo);
    }
}
