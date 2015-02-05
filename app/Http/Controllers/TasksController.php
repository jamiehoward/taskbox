<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Task;

use Illuminate\Http\Request;

class TasksController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $tasks = Task::where(array('completed_on'=>null, 'deleted_at' => null))->get();
        $completedTasks = Task::where('completed_on', '<>', "NULL")->limit(10)->get();
		return view('tasks.show_all', compact('tasks', 'completedTasks'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
    {
        $task = new Task;
        $task->description = $request->input('description');

        if ($task->save()):
		    return json_encode($task);
        else:
            return FALSE;
        endif;

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function complete(Request $request)
	{
        $task = Task::find($request->input('id'));
        $task->completed_on = date("Y-m-d H:i:s");

        if ($task->save()):
            return json_encode($task);
        else:
            return FALSE;
        endif;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
    {
        $task = Task::find($request->input('id'));
        $task->deleted_at = date("Y-m-d H:i:s");

        if ($task->save()):
            return json_encode(true);
        endif;
	}

    public function show(Request $request)
    {
        $task = Task::find($request->input('id'));

        header("Content-type:application/json");
        return json_encode($task);
    }

    public function update(Request $request)
    {
        $task = Task::find($request->input('id'));
        $task->description = $request->input('description');

        header("Content-type:application/json");

        if ( $task->save() ):
            $data = ['success' => true, 'task' => $task];
        else:
            $data = ['success' => false];
        endif;

        return json_encode($data);
    }

}
