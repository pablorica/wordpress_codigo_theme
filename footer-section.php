    <!-- footer -->
    <footer class="footer section">
        <div class="d-flex flex-column">
            <div class="container m-auto">
                <div class="row">
                    <div class="col-md-8 mx-auto text-center footer-media">
                        <?php if( have_rows('footer_media_cta','option') ): ?>
                            <ul class="entry-media px-3">
                            <?php while ( have_rows('footer_media_cta','option') ) : the_row(); 
                                    $icon = wp_get_attachment_image(get_sub_field('icon'), 'thumbnail');
                                    $url = get_sub_field('link');
                            ?>
                                <li class="footer-media--item">
                                    <a href="<?=$url?>" target="_blank"><?=$icon?></a>
                                </li>
                            <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center p-2 footer-copyright">
                    <!-- copyright -->
                    <?php the_field('footer_copyright','option'); ?>
                    <!-- /copyright -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div><!-- /.d-flex -->
    </footer>
    <!-- /footer -->
