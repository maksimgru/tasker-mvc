<?php include '../app/Views/common/header.php'; ?>

<?php if ($data['newTaskID']) { ?>

    <div class="form-message text-center alert alert-success">
        Success!!! <br> New task has been created with ID = <?php echo $data['newTaskID']; ?>
    </div>

<?php } ?>

<?php include '../app/Views/task/form.php'; ?>

<?php include '../app/Views/common/footer.php'; ?>
