<?php if (!$data['isValidForm']) { ?>
    <div class="form-message text-center alert alert-danger">
        <?php echo $data['errorMessage']; ?>
    </div>
<?php } ?>

<div class="row"><div class="col-md-4 offset-md-4">
<form name="login-form" action="<?php echo $data['formAction']; ?>" method="POST">

    <div class="form-row mb-4">
        <div class="col-md-12">
            <?php $error = $data['formErrors']['name']; ?>
            <label for="name" class="font-size">Name</label>
            <input id="name"
                   class="form-control <?php echo $error ? 'is-invalid' : ''; ?>"
                   name="name"
                   value="<?php echo $data['formData']['name']; ?>"
                   type="text"
                   placeholder="Enter name"
            >
            <?php if ($error) { ?>
                <div class="invalid-feedback"><?php echo $error; ?></div>
            <?php } ?>
        </div>
    </div>

    <div class="form-row mb-4">
        <div class="col-md-12">
            <?php $error = $data['formErrors']['password']; ?>
            <label for="name" class="font-size">Password</label>
            <input id="password"
                   class="form-control <?php echo $error ? 'is-invalid' : ''; ?>"
                   name="password"
                   value="<?php echo $data['formData']['password']; ?>"
                   type="password"
                   placeholder="Enter password"
            >
            <?php if ($error) { ?>
                <div class="invalid-feedback"><?php echo $error; ?></div>
            <?php } ?>
        </div>
    </div>

    <div class="form-row mb-4">
        <div class="col-md-6">
            <input type="hidden" name="redirect_to" value="<?php echo $data['redirectTo']; ?>" />
            <button type="submit" name="submit" value="<?php echo $data['submitAction']; ?>" class="btn btn-primary">
                <?php echo $data['submitLabel']; ?>
            </button>
        </div>
    </div>

</form>
</div></div>
