function getAccountDebitCredit() {
    $.ajax({
        url: 'controleur.php',
        type: 'post',
        data: {functionname: 'test'},
        success: function(response) {
           displayAccountInSelect(response);
           
        },
     });
}

function displayAccountInSelect($listAccount) {
    var output = '';
    array.forEach(element => {
        output += '<option value="'+element+'">'+element+'</option>'; //a finir
    });
}