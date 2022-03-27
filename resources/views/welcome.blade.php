<div class="BtdShopifySingleItemTableOfContents sidebarToc">
    {!! $tocOutput !!}
</div>

{!! $content !!}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>

    if ($(window).width() < 991) {
        $('.BtdShopifySingleItemTableOfContents h3').click(function () {
            $('.BtdShopifySingleItemTableOfContents ol').slideToggle();
        })
    }
</script>
