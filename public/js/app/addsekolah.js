    $( document ).ready( function () {
            $( "#updForm" ).validate( {
                rules: {
                    nmsekolah: "required",
                    qrdesa: {
                        required: true,
                        minlength: 2
                    },
                    npsn:"required",
                },
                messages: {
                    nmdesa: "Nama Sekolah harus diisi",
                    qrkecamatan: {
                        required: "Nama Desa harus diisi",
                        minlength: "Minimal 2 karakter",
                    },
                    npsn: "NPSN harus diisi",
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
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nmdesa'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: jsUrl+"?query=%QUERY",
    wildcard: '%QUERY'
  }
});

$('#srcDesa').typeahead(null, {
  name: 'srcDesa',
  display: 'nmdesa',
  source: bestPictures,
    templates: {
    empty: [
      '<div class="empty-message">',
        'SILAHKAN PILIH DESA',
      '</div>'
    ].join('\n'),
    suggestion: Handlebars.compile('<div><strong>{{nmdesa}}</strong> – {{nmkecamatan}}</div>')
  }

}).on('typeahead:selected', function(event, selection) {
 $('#id_desa').val(selection.id);
});

;


})
