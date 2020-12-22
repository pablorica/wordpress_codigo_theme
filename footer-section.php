    <!-- footer -->
    <footer class="footer section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 d-flex flex-column">
                        <?php the_field('footer_left','option'); ?>
                    </div>
                    <div class="col-md-4 d-flex flex-column">
                        <?php the_field('footer_middle','option'); ?>
                    </div>
                    <div class="col-md-4 d-flex flex-column">
                        <?php the_field('footer_right','option'); ?>

                        <?php if( have_rows('footer_media_cta','option') ): ?>
                            <ul class="footer-media mt-auto">
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
                </div><!-- /.row -->
            </div><!-- /.container -->
    </footer>
    <!-- /footer -->
    

