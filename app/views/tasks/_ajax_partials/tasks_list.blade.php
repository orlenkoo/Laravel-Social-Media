<div class="row expanded">
    <div class="large-12 columns">
        @include('tasks._partials.tasks_table')
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <div class="panel-list-pagination" id="pagination_tasks_list">
            <?php echo $tasks->links(); ?>
        </div>
    </div>
</div>

<br>
<div class="row expanded">
    <div class="large-4 columns pull-left">
        <input type="button" value="Check all" class="button tiny primary" onclick="markAllAsDone();">
        <input type="button" value="Uncheck All" class="button tiny primary" onclick="UnmarkAllAsDone();">
    </div>
    <div class="large-8 columns">
        &nbsp;
    </div>
</div>

