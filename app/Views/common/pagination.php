<?php if ($data['paginationLinks']['previous'] || $data['paginationLinks']['next']) { ?>
<nav>
    <ul class="pagination justify-content-center">
        <?php if ($data['paginationLinks']['previous']) { ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo $data['paginationLinks']['previous']; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
        <?php } ?>

        <?php foreach ($data['paginationLinks']['paged'] as $i => $link) { ?>
            <li class="page-item <?php echo $link['isActive'] ? 'active' : ''; ?>">
                <a class="page-link" href="<?php echo $link['value']; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>

        <?php if ($data['paginationLinks']['next']) { ?>
            <li class="page-item ">
                <a class="page-link" href="<?php echo $data['paginationLinks']['next']; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
<?php } ?>
