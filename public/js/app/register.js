var checkRegister = checked => {
    var ischecked = $("#agreeTerms").prop("checked");
    if (ischecked && checked) {
        $("#register-btn").attr("disabled", false);
    } else {
        $("#register-btn").attr("disabled", true);
    }
};

$(function() {
    checkRegister(false);
    $("input:text, input:password").each((i, e) => {
        $(this).change(() => {
            var ischecked = e.value !== "" ? true : false;
            checkRegister(ischecked);
        });
    });

    $("#agreeTerms").click(v => checkRegister());
    var bestPictures = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("nm_sekolah"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: jsUrl + "?query=%QUERY",
            wildcard: "%QUERY"
        }
    });
});
