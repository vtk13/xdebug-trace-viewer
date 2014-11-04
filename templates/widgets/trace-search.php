<div class="panel panel-default">
    <div class="panel-heading">Search for file/function name</div>
    <div class="panel-body">
        <form action="/trace/search" method="GET">
            <div>
                <input type="hidden" name="trace" value="<?php echo isset($_GET['trace']) ? htmlspecialchars($_GET['trace']) : '' ?>">
                ~<input type="text" name="term" value="<?php echo isset($_GET['term']) ? htmlspecialchars($_GET['term']) : '' ?>"
                       placeholder="regexp by function name">~<input style="width: 30px;" type="text" name="mod" value="<?php echo isset($_GET['mod']) ? htmlspecialchars($_GET['mod']) : 'i' ?>">
                <button type="submit">Search</button>
            </div>
        </form>
    </div>
</div>