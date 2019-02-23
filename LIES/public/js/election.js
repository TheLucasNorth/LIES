$(document).ready(function () {
    $(".test").each(function () {
        var $self = $(this);
        $self.data("previous_value", $self.val());
    });

    $(".test").on("change", function () {
        var $self = $(this);
        var prev_value = $self.data("previous_value");
        var cur_value = $self.val();

        $(".test").not($self).find("option").filter(function () {
            return $(this).val() == prev_value;
        }).prop("disabled", false);

        if (cur_value != "") {
            $(".test").not($self).find("option").filter(function () {
                return $(this).val() == cur_value;
            }).prop("disabled", true);

            $self.data("previous_value", cur_value);
        }
    });
});