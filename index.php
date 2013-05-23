<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Snippets Module</title>
     <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js" type="text/javascript"></script>
    <script src="js/snippets.jquery.js" type="text/javascript"></script>

    <script type="text/javascript">

    jQuery(document).ready(function($){
        var first_char       = '#';
        var ascii_first_char = first_char.charCodeAt(0)
        var cache            = {};
        var input            = $( "#drug" );

        input.on("keypress", function(e){
            if(e.which == ascii_first_char) {
                    input.autocomplete(
                    {
                        source: function( request, response ) {
                            var term = request.term;
                            if ( term in cache ) {
                                response( cache[ term ] );
                                return;
                            }

                            var text  = input.val();

                            var regexp = new RegExp(first_char, 'g');

                            var words = text.split(regexp);

                            var term = words[words.length-1];


                            $.getJSON( "jsons/snippet.php?term="+term, function( data, status, xhr ) {
                                cache[ term ] = data;
                                response( data );
                            });
                        },
                        minLength: 1,
                        open: function( event, ui ) {
                            input.keyup(function(e){
                               if(e.keyCode == 32) {
                                   // user has pressed space
                                     $("#drug").autocomplete('search');
                                }
                            });
                        },
                        select: function( event, ui) {
                            alert('a');
                            var text  = input.val();
                            var regexp = new RegExp(first_char+ui.item.label, 'gi');
                            new_text = text.replace(regexp, ui.item.value);
                            input.val(new_text);
                            //index.on( "autocompleteselect");
                            return false; // Prevent the widget from inserting the value.
                        }
                    }
                ).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.value+ " <span class='ui-icon ui-icon-close' style='float: right;'></span></a>" )
                    .appendTo( ul );
                };
                input.autocomplete('enable');
            }
        });

});
    </script>
</head>
<body>
    <?php

    require_once("snippet.class.php");
    $snippet = new Snippet('localhost', 'root', '', 'snippet_db', '*');
    var_dump($snippet->snippet_suggestion('*ro'));
die();
    echo $snippet->replace_string("Good morning #ro!!!", "ro");
    echo $snippet->parse_string('#ro #ffffff333dsd #333_ujg sfkjkdshfds #nlp #la #fdskjfhd $kdshfdks fdskkfjds #nlp!');
    var_dump($snippet->snippet_suggestion('l')) ;
    //Result:

    // Good morning Los Angeles!!!

    ?>
<div>
    <form>
        <label for="drug">Drug name: </label>
        <textarea class="info" id="drug"></textarea>
        <label for="comp">Composition: </label>
        <input type="text" class="info" id="comp" value="/20mg"/>
    </form>
</div>
</body>
</html>
