<h2 class="subtitle">Welcome to your dashboard Employee!</h2>
<p>What will you be doing today?</p>

<hr>
<h3 class="subtitle">Task Management</h3>


<div class="container" id="resultsContainer">
    <?php 
        use Framework\Views\Layout;
        
        Layout::contentRender('tasks/view');
    ?>
</div>