<?php
/*
 * Template Name: Tumblr User Activity
 */

$tumblr_username = 'amycodes';

function readTumblr() {
    
    $json_url = "http://" . $tumblr_username . ".tumblr.com/rss";
    $ch = curl_init($json_url);
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    $res = simplexml_load_string($result);
    $json = json_encode($res);
    $array = json_decode($json, true);
    $tumblr_feed = $array['channel']['item'];
    return $tumblr_feed;
}

$tumblr_feed = readTumblr();

function make_one_column($classes) {
    $idx = array_search('page-two-column', $classes);
    unset($classes[$idx]);
    $classes[] = 'page-one-column';

    return $classes;
}

add_filter('body_class', 'make_one_column');
get_header();
?>


<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <h1>On Tumblr</h1>
            <?php  echo "<article><div class='entry-content'><p>" . get_post_field( 'post_content' , get_the_ID() ) . "</p></div></article>"; ?>
<?php if (count($tumblr_feed) > 0) : ?>
                <?php
                foreach ($tumblr_feed as $tumblr_post) {
                    $pubDate = new DateTime($tumblr_post['pubDate']);
                    ?>
                    <article>
                        <header class='entry-header'>
                            <div class="entry-meta">
        <?php echo $pubDate->format("F j, Y  g:i a"); ?>
                            </div><!-- .entry-meta -->
                            <h2 class="entry-title"><a href="<?php echo $tumblr_post['link']; ?>" rel="bookmark"><?php echo $tumblr_post['title']; ?> </a></h2>
                        </header>
                        <div class='entry-content'>
                            <p><?php echo $tumblr_post['description']; ?></p>
                        </div>
                        <?php 
                            if ( count($tumblr_post['category']) > 0 ) {
                                $tag_links = array();
                                foreach ( $tumblr_post['category'] as $category ) {
                                    $tag_links[] = "<a href='http://" . $tumblr_username . ".tumblr.com/tagged/" . str_replace(' ', '-', $category) . "'>$category</a>";
                                }
                        ?>
                            <footer class="entry-footer">
                                <span class="cat-tags-links">
                                    <span class="tags-links">
                                        <svg class="icon icon-hashtag" aria-hidden="true" role="img"><use href="#icon-hashtag" xlink:href="#icon-hashtag"></use> </svg>
                                        <span class="screen-reader-text">Tags</span>
                                        <?php echo implode(', ' , $tag_links); ?>
                                </span>
                            </footer> <!-- .entry-footer -->
                        <?php
                            }
                        ?>

                    </article>
                    <hr/>
        <?php
    }
    ?>
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
