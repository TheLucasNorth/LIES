$(document).ready(function () {


    $("#resetddls").on("click", function () {
        $("select option").show();
    });
    $('.hello').change(function () {
        var val = $(this).val();
        var sel = $(this);

        if (val != "") {
            if (sel.data("selected")) {
                var oldval = sel.data("selected");
                sel
                    .siblings('select')
                    .append($('<option/>').attr("value", oldval).text(oldval));
            }

            sel
                .data("selected", val)
                .siblings('select')
                .children('option[value=' + val + ']')
                .remove();
        }
        else if (val == "") {
            if (sel.data("selected")) {
                var oldval = sel.data("selected");
                sel
                    .removeData("selected")
                    .siblings('select')
                    .append($('<option/>').attr("value", oldval).text(oldval));
            }
        }
    });
    $("#admin-button").on("click", function () {
        location.reload(true);
    });
    $(".admin-button").on("click", function () {
        location.reload(true);
    });

});