<?php include '../app/Views/common/header.php'; ?>

<?php if ($data['newTaskID']) { ?>

    <div class="form-message text-center alert alert-success">
        <p>Success!!!</p>
        <p>New task has been created with ID =<?php echo $data['newTaskID']; ?></p>
        <div><a href="<?php echo \App\Core\Helpers::path('task/table'); ?>" class="btn btn-dark mt-4">Got to tasks list</a></div>
    </div>

<?php } else { ?>

<?php include '../app/Views/task/form.php'; ?>

<?php } ?>

<?php include '../app/Views/common/footer.php'; ?>
