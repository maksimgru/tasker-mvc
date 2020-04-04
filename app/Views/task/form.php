<?php if (!$data['isValidForm']) { ?>
    <div class="form-message text-center alert alert-danger">
        <?php echo $data['errorMessage']; ?>
    </div>
<?php } ?>

<form name="task-form" action="<?php echo $data['formAction']; ?>" method="POST">

    <div class="form-row mb-4">

        <div class="col-md-6">
            <?php $error = $data['formErrors']['username']; ?>
            <label for="username" class="font-size">Username</label>
            <input id="username"
                   class="form-control <?php echo $error ? 'is-invalid' : ''; ?>"
                   name="username"
                   value="<?php echo $data['formData']['username']; ?>"
                   type="text"
                   placeholder="Enter username"
            >
            <?php if ($error) { ?>
                <div class="invalid-feedback"><?php echo $error; ?></div>
            <?php } ?>
        </div>

        <div class="col-md-6">
            <?php $error = $data['formErrors']['email']; ?>
            <label for="email">Email</label>
            <input id="email"
                class="form-control <?php echo $error ? 'is-invalid' : ''; ?>"
                name="email"
                value="<?php echo $data['formData']['email']; ?>"
                type="text"
                placeholder="Enter email"
            >
            <?php if ($error) { ?>
                <div class="invalid-feedback"><?php echo $error; ?></div>
            <?php } ?>
        </div>

    </div>

    <div class="form-row mb-4">
        <div class="col-md-12">
            <?php $error = $data['formErrors']['description']; ?>
            <label for="description">Description</label>
            <textarea id="description"
                class="form-control <?php echo $error ? 'is-invalid' : ''; ?>"
                name="description"
                placeholder="Description"
            ><?php echo $data['formData']['description']; ?></textarea>
            <?php if ($error) { ?>
                <div class="invalid-feedback"><?php echo $error; ?></div>
            <?php } ?>
        </div>
    </div>

    <?php if ($data['isAdminAuth']) { ?>
        <div class="form-row mb-4">
            <div class="col-md-12">
                <div class="form-check">
                    <input id="completed"
                        class="form-check-input"
                        name="status"
                        value="1"
                        type="checkbox"
                        <?php echo $data['formData']['status'] ? 'checked' : ''; ?>
                    >
                    <label class="form-check-label" for="completed">Completed</label>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="form-row mb-4">
        <div class="col-md-6">
            <button type="submit" name="submit" value="<?php echo $data['submitAction']; ?>" class="btn btn-primary">
                <?php echo $data['submitLabel']; ?>
            </button>
        </div>
    </div>

</form>
