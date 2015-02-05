@extends ('app')

@section ('style')
    {!! HTML::style('/css/tasks.css') !!}
@endsection

@section ('content')
    {{--<div class="col-lg-6 col-lg-offset-4 col-md-6 col-md-offset-4 col-sm-6 col-sm-offset-4 col-xs-10 col-xs-offset-1">--}}
    <div class="col-lg-12">

        <div class="row col-lg-12">
                <h1 class="header-logo">TaskBox<small>.dev</small></h1>
        </div>

        <div class="row task-list-container col-lg-12">
            <ul class="list-unstyled" id="task-list">
            @if (count($tasks))
                @foreach ($tasks as $task)
                    <li class="task-item">
                        <h3>
                            {!! Form::checkbox('completed', null, null, array('class'=>'task-check', 'data-taskID' => $task->id)) !!}
                            <span class="task-description">{{ $task->description }}</span> <i class="fa fa-times task-delete"></i>
                        </h3>
                    </li>
                @endforeach
            @endif
            </ul>
        </div>

        <div class="row col-lg-12">
             {!! Form::open(array('name'=>'new-task-item', 'url'=>'#', 'method' => 'GET')) !!}
                {!! Form::text('description', null, array('id'=>'new-task-item', 'autcomplete'=>'off', 'placeholder'=>" + Add a new task")) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
            {!! Form::close() !!}
        </div>

        <div class="row col-lg-12" style="margin-top:25px;">
            <h4>Completed tasks</h4>
            <div id="completed-tasks-container">
                <ul class='list-unstyled' id='completed-tasks'>
                @foreach ($completedTasks as $task)
                    <li class="task-completed-item"><i class='fa fa-check-square-o'></i><em>{{ $task->description }}</em> - <small>Completed @ {{ $task->completed_on }}</small></li>
                @endforeach
                </ul>
            </div>
        </div>

    </div>

    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" arial-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="taskModalLabel">Edit task details</h4>
                </div>
                <div class="modal-body" id="taskModalDetails">
                    {{--{!! Form::open(array('url'=>'#')) !!}--}}
                    <div class="form-group">
                        {!! Form::label('description', 'Task description') !!}
                        {!! Form::text('description', null, array("id"=>"task-edit-description", "class" => "form-control")) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('created_at', 'Created on') !!}
                        {!! Form::text('created_at', null, array("id"=>"task-edit-created", "class" => "form-control", "disabled")) !!}
                    </div>
                    {!! Form::hidden('task-edit-id', null, array("id"=>"task-edit-id")) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {!! Form::submit('Save changes', array('class'=>'btn btn-primary', 'id' => 'task-edit-submit')) !!}
                    {{--{!! Form::close() !!}--}}

                </div>
            </div>
        </div>
    </div>

@endsection

@section ('scripts')
    {!! HTML::script('/js/tasks_show_all.js') !!}
@endsection