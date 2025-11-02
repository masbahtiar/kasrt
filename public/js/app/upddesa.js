    $( document ).ready( function () {
            $( "#updForm" ).validate( {
                rules: {
                    nmdesa: "required",
                    qrkecamatan: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    nmdesa: "Masukkan Nama Desa",
                    qrkecamatan: {
                        required: "Nama Kecamatan harus diisi",
                        minlength: "Minimal 2 karakter"
                    }
                },
                errorElement: "em",
                errorPlacement : function ( error, element ) {
                    // Add the `help-block` class to the error element
                    error.addClass( "help-block" );

                    if ( element.prop( "type" ) === "checkbox" ) {
                        error.insertAfter( element.parent( "label" ) );
                    } else {
                        error.insertAfter( element );
                    }
                },
                highlight: function ( element, errorClass, validClass ) {
                    $( element ).parents( ".col-md-8" ).addClass( "has-error" ).removeClass( "has-success" );
                },
                unhighlight: function ( element, errorClass, validClass ) {
                    $( element ).parents( ".col-md-8" ).addClass( "has-success" ).removeClass( "has-error" );
                }
            } );

var bestPictures = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nmkecamatan'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: jsUrl+"?query=%QUERY",
    wildcard: '%QUERY'
  }
});

$('#srcKecamatan').typeahead(null, {
  name: 'srcKecamatan',
  display: 'nmkecamatan',
  source: bestPictures,
    templates: {
    empty: [
      '<div class="empty-message">',
        'SILAHKAN PILIH KECAMATAN',
      '</div>'
    ].join('\n'),
    suggestion: Handlebars.compile('<div><strong>{{nmkecamatan}}</strong> – {{nmkabupaten}}</div>')
  }

}).on('typeahead:selected', function(event, selection) {
 $('#id_kec').val(selection.id);
 console.log(selection.id);
});


})
