$(document).ready(function() {
    var bestPictures = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("nm_sekolah"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: jsUrl + "?query=%QUERY",
            wildcard: "%QUERY"
        }
    });

    $("#srcSekolah")
        .typeahead(null, {
            name: "srcSekolah",
            display: "nm_sekolah",
            source: bestPictures,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    "SILAHKAN PILIH SEKOLAH",
                    "</div>"
                ].join("\n"),
                suggestion: Handlebars.compile(
                    "<div><strong>{{nm_sekolah}}</strong> – {{npsn}}</div>"
                )
            }
        })
        .on("typeahead:selected", function(event, selection) {
            $("#sekolah_id").val(selection.id);
        });
});
