$('[name="enable_password_protection"]').on('click', function(){
    if($('[name="enable_password_protection"]:checked').val() === '1'){
        $('[name="new_password"]').prop('disabled', false);
        $('[name="repeat_new_password"]').prop('disabled', false);
    }else{
        $('[name="new_password"]').prop('disabled', true);
        $('[name="repeat_new_password"]').prop('disabled', true);
    }
});
$('[name="enable_password_protection"]:checked').click();
