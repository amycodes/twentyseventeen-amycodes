<?php
/*
 * Template Name: Git Hub User Activity
 */

get_header();

function readGIT() {
    $json_url = "https://github.com/amycodes.atom";
    $ch = curl_init($json_url);
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);

    $p = xml_parser_create();
    xml_parse_into_struct($p, $result, $vals, $index);
    xml_parser_free($p);
    return $vals;
}

$git_feed = readGIT();
?>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php if (count($git_feed) > 0) : ?>
            <?php else: ?>
                <article>
                    <header class="entry-header">
                        <h1>GITHUB</h1>
                    </header><!-- .entry-header -->
                    <div class="entry-content">
                        There is no recent activity.
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . __('Pages:', 'twentyseventeen'),
                            'after' => '</div>',
                            'link_before' => '<span class="page-number">',
                            'link_after' => '</span>',
                        ));
                        ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-## -->
            <?php endif; ?>


        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
