<h2 class="subtitle">Welcome to your dashboard Manager!</h2>
<p>What will you be doing today?</p>

<hr>
<h3 class="subtitle">Task Management</h3>

<div class="is-flex is-flex-wrap-wrap is-justify-content-space-evenly my-2">

    <a href="/task/create">
        <button class="button is-primary mx-2 my-2">Create Task</button>
    </a>

    <a href="/dashboard/manager?action=viewTasks">
        <button class="button is-primary mx-2 my-2">View My Tasks</button>
    </a>

    <a href="/dashboard/manager?action=filterTasks">
        <button class="button is-primary mx-2 my-2">Filter Tasks</button>
    </a>

</div>

<div class="container" id="resultsContainer">
    <?php 
        use Framework\Views\Layout;
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'viewTasks':
                    Layout::contentRender('tasks/view');
                    break;
                case 'filterTasks':
                    Layout::contentRender('tasks/filter');
                    break;
            }
        }
    ?>
</div>