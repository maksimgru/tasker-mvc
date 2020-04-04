<?php include '../app/Views/common/header.php'; ?>

<?php if ($data['updated']) { ?>

    <div class="form-message text-center alert alert-success">
         Task has been updated!!!
    </div>

<?php } ?>

<?php include '../app/Views/task/form.php'; ?>

<?php include '../app/Views/common/footer.php'; ?>
