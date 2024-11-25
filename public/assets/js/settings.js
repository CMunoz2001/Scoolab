
function formValidate(){

    $input = document.getElementById('deleteCheck');

    $deleteButton = document.getElementById('deleteButton');

    if ($input.checked == true) {
        $deleteButton.disabled = false;
    }else{
        $deleteButton.disabled = true;
    }

}