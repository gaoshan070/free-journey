<?php $protocol = wp_get_server_protocol(); header( "$protocol 503 Service Unavailable", true, 503 ); header( 'Content-Type: text/html; charset=utf-8' ); header( 'Retry-After: 600' ); ?>
<html>
<head>
    <meta charset="utf-8">
    <title>Sweet gift is updating...</title>
</head>
<body>
<div class="main-container">
    <div class="container">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <section class="error-404 not-found">
                    <div class="images">
                        <img src="/wp-includes/images/sweet_logo.png">
                    </div>
                    <h1 class="page-title">
                        This website is updating.....
                    </h1>
                    <p class="page-content">
                    </p><!-- .page-content -->
                </section><!-- .error-404 -->
            </main><!-- #main -->
        </div><!-- #primary -->
    </div>
</div>

</body>
</html>