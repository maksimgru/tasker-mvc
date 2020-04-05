<?php use App\Core\Helpers; ?>

<?php include '../app/Views/common/header.php'; ?>

<?php if ($data['tasks']) { ?>

<table  class="table table-bordered table-hover">
    <thead>
        <tr>
            <?php foreach($data['columnsMeta'] as $col) { ?>
                <th class="<?php echo $col['htmlClasses']; ?>">
                    <a <?php echo $col['orderbyUri'] ? 'href="' . $col['orderbyUri'] . '"' : ''; ?> >
                        <?php echo $col['columnName']; ?>
                    </a>
                    <span class="arrow"></span>
                </th>
            <?php } ?>
            <?php if (Helpers::isAdminAuth()) { ?>
                <th></th>
            <?php } ?>
        </tr>
    </thead>

    <tbody>
        <?php foreach($data['tasks'] as $task) { ?>
            <tr>
                <td><?php echo $task['id']; ?></td>
                <td><?php echo $task['username']; ?></td>
                <td><?php echo $task['email']; ?></td>
                <td class="font-weight-bold">
                    <?php if ($task['status']) { ?>
                        <span class="text-success">Completed</span>
                    <?php } else { ?>
                        <span class="text-warning">In progress</span>
                    <?php } ?>
                    <?php if ($task['updated_at']) { ?>
                        <p class="text-info">Edited by admin</p>
                    <?php } ?>
                </td>
                <td><?php echo nl2br($task['description']); ?></td>
                <?php if (Helpers::isAdminAuth()) { ?>
                    <td><a href="<?php echo Helpers::path('task/edit/' . $task['id']); ?>" class="edit-user">Edit</a></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php } else { ?>
    <div class="row">
        <div class="col-md-12 display-4 text-center text-muted mb-4">
            <em>No tasks found ...</em>
        </div>
        <div class="col-md-12 text-center">
            <a href="<?php echo Helpers::path('task/create'); ?>" class="btn btn-dark btn-lg">Create First Task</a>
        </div>
    </div>
<?php } ?>

<?php include '../app/Views/common/pagination.php'; ?>

<?php include '../app/Views/common/footer.php'; ?>
