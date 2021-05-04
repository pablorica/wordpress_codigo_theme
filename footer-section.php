    <!-- footer -->
    <footer class="footer section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 d-flex flex-md-column order-4 order-md-1">
                        <?php the_field('footer_left','option'); ?>
                        <?php if( have_rows('footer_media_cta','option') ): ?>
                            <ul class="footer-media d-md-none ml-3 ">
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
                    <div class="col-md-3 d-none d-md-flex flex-column order-md-2">
                        <?php if( have_rows('footer_media_cta','option') ): ?>
                            <ul class="footer-media">
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
                    <div class="col-md-3 d-flex flex-column order-1 order-md-3">
                        <?php the_field('footer_middle','option'); ?>
                    </div>
                    <div class="col-md-3 d-flex flex-column order-2 order-md-4 mb-4 mb-md-0">
                        <?php the_field('footer_right','option'); ?>
                    </div>

                    <div class="col-9 col-md-6 offset-md-6 d-flex flex-column footer-copyright order-3 order-md-5 mb-4 mb-md-0">
                        <div class="mt-auto">
                            <?php the_field('footer_copyright','option'); ?>
                        </div>
                    </div>
                </div><!-- /.row -->

            </div><!-- /.container -->
    </footer>
    <!-- /footer -->
    

