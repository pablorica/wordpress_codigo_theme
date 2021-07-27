    <!-- footer -->
    <footer class="footer section">
            <div class="container-fluid">
                <div class="row no-gutters">
                    <div class="col-xl-4 d-flex flex-column px-1 mb-5 mb-md-0 column-1">
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
                    
                    <div class="col-md-6 col-xl-4 d-flex flex-column px-1 mt-5 mt-xl-0 column-2">
                        <?php the_field('footer_middle','option'); ?>
                    </div>
                    <div class="col-md-6 col-xl-4  d-flex flex-column px-1 mt-5 mt-xl-0 column-3">
                        <?php the_field('footer_right','option'); ?>

                        <!-- Social media -->
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
                        <!-- /.Social media -->

                    </div>

                    <div class="col-xl-4 offset-xl-4 d-flex flex-column footer-copyright mt-5 mt-xl-4 px-1 column-4">
                        <div class="mt-auto">
                            <?php the_field('footer_copyright','option'); ?>
                        </div>
                    </div>
                    

                    


                </div><!-- /.row -->

            </div><!-- /.container -->
    </footer>
    <!-- /footer -->
<style>
    footer.footer {
        background-color: <?php echo (get_field('footer_background_color','option') ? get_field('footer_background_color','option') : "#4E5780") ?>;
        color: <?php echo (get_field('footer_color','option') ? get_field('footer_color','option') : "#F89ABA") ?>;
        border-top: 1px solid <?php echo (get_field('footer_color','option') ? get_field('footer_color','option') : "#F89ABA") ?>;
    }
</style>


