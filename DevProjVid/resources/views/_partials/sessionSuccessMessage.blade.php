<?php
if (session()->has('success')) {
    ?>
    <div class="alert alert-success">
        <?php
        echo session('success');
        ?>
    </div>
    <?php
}
?>