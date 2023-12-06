$(document).ready(function() {

    $("#addQuantity").click(function() {
        var currentQuantity = parseInt($("#quantity").val());
        $("#quantity").val(currentQuantity + 1);
    });


    $("#subtractQuantity").click(function() {
        var currentQuantity = parseInt($("#quantity").val());
        if (currentQuantity > 1) {
            $("#quantity").val(currentQuantity - 1);
        }
    });
});