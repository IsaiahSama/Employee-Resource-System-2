<h2 class="subtitle">Welcome to your dashboard admin!</h2>
<p>What will you be doing today?</p>

<hr>
<h3 class="subtitle">User Management</h3>
<div class="is-flex is-flex-wrap-wrap is-justify-content-space-evenly my-2">

    <a href="/dashboard/admin?action=addUser">
        <button class="button is-primary my-1">Add Employee</button>
    </a>

    <a href="/dashboard/admin?action=viewUsers">
        <button class="button is-primary my-1">View All Users</button>
    </a>

</div>

<hr>
<h3 class="subtitle">Task Management</h3>

<div class="is-flex is-flex-wrap-wrap is-justify-content-space-evenly my-2">

    <a href="/task/create">
        <button class="button is-primary">Create Task</button>
    </a>

    <a href="/dashboard/admin?action=viewTasks">
        <button class="button is-primary">View All Tasks</button>
    </a>

</div>

<div class="container" id="resultsContainer">
    <?php 

    use Framework\Views\Layout;

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'addUser':
                    Layout::contentRender('users/create');
                    break;
                case 'viewUsers':
                    Layout::contentRender('users/view');
                    break;
                case 'viewTasks':
                    Layout::contentRender('tasks/view');
                    break;
            }
        }
    ?>
</div>