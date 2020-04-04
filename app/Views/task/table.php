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
                </td>
                <td><?php echo $task['description']; ?></td>
                <?php if (Helpers::isAdminAuth()) { ?>
                    <td><a href="<?php echo Helpers::path('task/edit/' . $task['id']); ?>" class="edit-user">Edit</a></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php } else { ?>
    <div class="display-4 text-center text-muted"><em>No tasks ...</em></div>
<?php } ?>

<?php include '../app/Views/common/pagination.php'; ?>

<?php include '../app/Views/common/footer.php'; ?>
