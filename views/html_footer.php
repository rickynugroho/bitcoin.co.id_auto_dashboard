    </div>

    <script>
    var list_of_currency = <?php echo json_encode($list_of_currency)?>;
    </script>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/autoNumeric.min.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/app.js"></script>
    
    <?php
    foreach($js_files as $js_file){
        ?>
        <script src="<?php echo $js_file;?>"></script>
        <?php
    }
    ?>
</body>
</html>
