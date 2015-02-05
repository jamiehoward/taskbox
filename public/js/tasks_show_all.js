/**
 * Created by jhoward on 2/4/15.
 */

$(".task-delete").hide();

var completedTasks = [];

var checkForTasks = function() {

    // Show X on hover
    $(".task-item").hover(
        function() {
            $(this).find('.task-delete').fadeIn(150);
        },
        function() {
            $(this).find('.task-delete').fadeOut(150);
        }
    );

    // Delete task on clicking X
    $(".task-delete").click(function(){
        var deleteButton = $(this);
        console.log(deleteButton.parent().find('.task-check').data('taskid'));
        $.ajax({
            url: siteURL + "/tasks/delete",
            data: {
                id : deleteButton.parent().find('.task-check').data('taskid'),
                _token :  $("#_token").val()
            },
            type: "post",
            success: function() {
                deleteButton.parent().parent().remove();
            },
            error: function() {
                alert('Sorry! Task could not be deleted!');
            }
        });
    });


    // Checking box will mark as completed
    $(".task-check").click(function() {
        var checkbox = $(this),
        taskID = $(this).data('taskid');

        $.ajax({
            url: siteURL + '/tasks/complete',
            type: "post",
            data: { id: taskID, _token :  $("#_token").val() },
            success: function (response){
                var taskDescription = checkbox.parent().find('span').html();

                checkbox.parent().parent().fadeOut(1000);
                checkbox.parent().parent().delay(1000).remove;
                completeTask(taskDescription);
            },
            error: function() {
                alert('Sorry! Task could not be marked off');
            }
        });

    });
};

var completeTask = function(taskDescription) {
    // Format the timestamp
    var d = new Date(),
    completedDate = (d.getMonth()+1) + '/' + d.getDate() + '/' + d.getFullYear() + ' @ ' + d.toLocaleTimeString();

    // Append the note of completed task to the completed task list
    var newTask = "<li class='task-completed-item'><i class='fa fa-check-square-o'></i><em>" + taskDescription + "</em> - <small>Completed " + completedDate + "</small></li>";

    // Bandaid fix bug where multiple tasks being added
    if ($.inArray(newTask, completedTasks) == -1)
    {
        completedTasks.push(newTask);
        $("#completed-tasks").append(newTask);
    }
};

// Keeping this process separate to build upon later
var refreshCompleted = function() {
    $("#completed-tasks-container").html("<ul class='list-unstyled' id='completed-tasks'></ul>");
    $(completedTasks).each(function(index, task){
        $("#completed-tasks").append(task);
    });
};

// When pressing enter on the text field
$("#new-task-item").keypress(function(e) {
        var key = e.which;
        if (key == 13) {

            e.preventDefault();
            $(".loading-overlay").show();

            $.ajax({
                url: siteURL + "/tasks/create",
                type: "post",
                data: {description : $("#new-task-item").val(), _token :  $("#_token").val()},
                success: function(response) {
                    var task = JSON.parse(response),
                        newTask = "<li class='task-item'><h3><input class='task-check' data-taskid='" + task.id + "' name='completed' type='checkbox'> <span class='task-description'>" + task.description + "</span> <i class='fa fa-times task-delete'></i></h3></li>";

                    $("#new-task-item").val("");
                    $("#task-list").append(newTask);
                    $(".task-delete").hide();
                    checkForTasks();
                    $(".loading-overlay").hide();
                },
                error: function() {
                    alert('Sorry, could not create the task');
                    $(".loading-overlay").hide();
                }
            });
        }
    });

// Init everything
checkForTasks();

// Open edit dialog
$(".task-description").click(function() {
    var taskDescription = $(this).html(),
        taskID = $(this).parent().find('.task-check').data('taskid');

    $(".loading-overlay").show();

    $.ajax({
        type:"post",
        url: siteURL + "/tasks/show",
        data: { id : taskID, _token :  $("#_token").val() },
        success: function(response) {
            var task = JSON.parse(response);

            $("#task-edit-description").val(task.description);
            $("#task-edit-created").val(task.created_at);
            $("#task-edit-id").val(task.id);

            $("#taskModal").modal('show');
            $(".loading-overlay").hide();
        },
        error: function() {
            $(".loading-overlay").hide();
            alert("Sorry! Could not pull up task information.");
        }
    });

});

// Submit edit dialog
$("#task-edit-submit").click(function() {
    var taskID = $("#task-edit-id").val(),
        taskDescription = $("#task-edit-description").val();
    $(".loading-overlay").show();
    $.ajax({
        type:"post",
        url: siteURL + "/tasks/update",
        data: {
            _token :  $("#_token").val(),
            description : taskDescription,
            id : taskID
        },
        success: function() {
            $("#taskModal").modal('hide');
            $("#task-list").find("[data-taskid=" + taskID + "]").parent().find('.task-description').html(taskDescription);
        }
    });
    $(".loading-overlay").hide();
});

