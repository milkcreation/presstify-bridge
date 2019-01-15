jQuery(document).ready(function($) {
    $(document).on('click', '[aria-control="notice"] [aria-toggle="dismiss"]', function(e){
        e.preventDefault();

        $(this).closest('[aria-control="notice"]').attr('aria-hide', 'true');
    });
});