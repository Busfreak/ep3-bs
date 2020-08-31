(function() {

    $(document).ready(function() {

        $("#sb-customization-panel-warning").remove();
        $("#sb-customization-panel").show();

        $("#sb-teacher").on("change keyup focusout", onTeacherChange);
        $("#sb-constructions").on("change keyup focusout", onConstructionsChange);

        onTeacherChange();
        onConstructionsChange();

    });

    function onTeacherChange() {
        var quantity = $("#sb-teacher").val();
        var sbButton = $("#sb-button");

        if (sbButton.length) {
            var oldHref = sbButton.attr("href");
            var newHref = oldHref.replace(/t=[0-9]+/, "t=" + quantity);

            sbButton.attr("href", newHref);
        }

        var askNamesPanel = $(".sb-teacher-names");

        if (askNamesPanel.length) {
            if (quantity > 1) {
                $(".sb-teacher-name").hide();

                for (var i = 2; i <= quantity; i++) {
                    $(".sb-teacher-name-" + i).show();
                }

                askNamesPanel.show();
            } else {
                askNamesPanel.hide();
            }

            $(window).trigger("squarebox.update");
        }

    }

    function onConstructionsChange() {
        var quantity = $("#sb-constructions").val();
        var sbButton = $("#sb-button");

        if (sbButton.length) {
            var oldHref = sbButton.attr("href");
            var newHref = oldHref.replace(/c=[0-9]+/, "c=" + quantity);

            sbButton.attr("href", newHref);
        }

    }

})();
