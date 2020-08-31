(function() {

    $(document).ready(function() {

        $("#sb-customization-panel-warning").remove();
        $("#sb-customization-panel").show();

        $("#sb-constructions").on("change keyup focusout", onConstructionsChange);

        onConstructionsChange();

    });

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
