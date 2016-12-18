(function() {

    // Side bar menu
    var hash = window.location.hash //Puts hash in variable
        ? window.location.hash
        :null ;
    var $topLinks = $('.sidebar .toctree-l1 li a');

    // Inject theme classes
    $('.sidebar > ul').addClass('sidenav').addClass('dropable').addClass('sticky');
    $('.sidebar > ul .current').addClass('active');

    // Check if has is selected
    var found = false;
    $topLinks.each(function (index, element) {
        if (element.hash == hash) {
            $(element).addClass('active');
            found = true;
        }
    });
    if (!found) { // if not, select the first one
        jQuery('.sidebar > ul .current ul a').first().addClass('active');
    }

    // Set the link as active upon click
    $topLinks.on('click', function (event) {
        $(this).parent('li').parent('ul').find('li > a').removeClass('active');
        $(this).addClass('active');
    });


    // Code blocks
    $('.highlight pre').each(function (index, element) {
        var className = $(element).parent('div').parent('div').attr('class');
        var languageName = className.replace('highlight-', '');
        $(element).attr('data-language', languageName);
        var content = $(element).html();
        var $code = $('<code />');

        $code.html(content);
        $(element).html($code);
        $code.addClass('language-'+languageName);
    });


    // Headers
    $('.headerlink').each(function (index, element) {
        var $parent = $(element).parent();
        if (!$parent.is('h1')) {
            $parent.attr('id', $(element).attr('href').substring(1));
        }
        $(element).remove();
    });

    // Callouts
    $('.admonition-title').each(function (i, element) {
        var $parent = $(element).parent();
        var $new = $('<h4 />');
        $new.html($(element).text());
        $parent.prepend($new);
        $(element).remove();
    });

    $('.admonition.note h4').prepend('<i class="fa fa-info"></i>&nbsp;');
    $('.admonition.important h4').prepend('<i class="fa fa-flash"></i>&nbsp;');
    $('.admonition.tip h4').prepend('<i class="fa fa-graduation-cap"></i>&nbsp;');
    $('.admonition.hint h4').prepend('<i class="fa fa-lightbulb-o"></i>&nbsp;');
    $('.admonition.warning h4').prepend('<i class="fa fa-warning"></i>&nbsp;');
    $('.admonition.attention h4').prepend('<i class="fa fa-exclamation"></i>&nbsp;');
    $('.admonition.error h4').prepend('<i class="fa fa-times-circle-o"></i>&nbsp;');
    $('.admonition.danger h4').prepend('<i class="fa fa-exclamation-triangle"></i>&nbsp;');
    $('.admonition.caution h4').prepend('<i class="fa fa-exclamation-triangle"></i>&nbsp;');

})(jQuery);