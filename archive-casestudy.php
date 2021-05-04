<?php get_header(); 


if(!isset($page_posts)) global $page_posts;
$parallax = get_field('pageoptions_parallax',$page_posts);

?>
<main  class="wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-0">



                        <?php 
                        $page_casestudies = get_page_by_title( 'Case Studies' );
                        $page_posts = $page_casestudies->ID; 
                        $page_title = get_the_title($page_posts);
                        get_template_part('global-templates/index-header'); 

                        $cstudies_post = get_post($page_posts);
                        $cstudies_ctnt = $cstudies_post->post_content;
                        $cstudies_ctnt = apply_filters('the_content', $cstudies_ctnt);

                        echo  $cstudies_ctnt;

                        ?>

                        <section class="container-fluid section-filter pt-5">
                            <div class="row no-gutters">

                                <div class="col-md-4 col-xl-3 p-0 d-flex filter-buttons pb-5">
                                    <a class="filter-trigger collapsed mr-auto" data-toggle="collapse" href="#collapseFilters" role="button" aria-expanded="false" aria-controls="collapseFilters">Filter</a>
     
                                    <div id="appButtons">
                                        <trigger-buttons></trigger-buttons>
                                    </div>
                                </div>


                                <div class="col-12 filters collapse" id="collapseFilters">
                                    <div id="appTaxonomies" class="pb-5">
                                        <filter-taxonomies></filter-taxonomies>
                                    </div>
                                </div>
                            

                            </div><!-- row end -->
                        </section><!-- container-fluid end -->

                        <div id="appCStudies">
                            <cstudies-list></cstudies-list>
                        </div>




                        <?php /* VUE templates */ ?>
                        <script type="text/html" id="trigger-buttons-tmpl">
                            <div>
                                <button type="button" class="btn btn-hidden vue_lodaded">Trigger</button>
                                <a class="btn-reset" v-on:click="resetAll()">Reset</a>
                            </div>
                        </script>
                        
                        <script type="text/html" id="filter-taxonomies-tmpl">
                            <div class="row no-gutters">
                                <?php 
                                $htmlLabels = '';
                                $htmlTabs   = '';
                                $htmlMobile = '';

                                $defaultSlug    = substr(str_shuffle(MD5(microtime())), 0, 5);
                                $defaultAcclug  = 'accordion-'.$defaultSlug;
                                $itemsByColumn  = 7;
                                $columClass     = 'col-lg-6 col-xl-4';

                                $taxnames = array('solution','region','sector');
                                foreach ( $taxnames as $taxname) {
                                    $htmlLabels .= '
                                    <li class="nav-item">
                                        <a class="nav-link" id="'.$taxname.'-tab" data-toggle="tab" href="#'.$taxname.'" role="tab" aria-controls="'.$taxname.'" aria-selected="false">By '.ucfirst($taxname).'</a>
                                    </li>';
                                    
                                    $htmlContent = '';
                                    $taxonomies = get_terms( array(
                                        'taxonomy' => $taxname,
                                        'hide_empty' => true,
                                    ));
                                    $rint    = 0;
                                    $openBox = false;
                                    foreach ( $taxonomies as $taxonomy) {
                                        //var_dump($taxonomy);
                                        if($rint%$itemsByColumn == 0) {
                                            $htmlContent .= '<div class="'.$columClass.'"><ul class="filter-results">';
                                            $openBox = true;
                                        }
                                        $htmlContent .= '
                                        <li>
                                            <label><input type="checkbox" id="'.$taxonomy->slug.'" value="'.$taxonomy->slug.'"  v-on:click="filterTaxonomy()"> '.$taxonomy->name.'</label>
                                        </li>';
                                        
                                        if($rint%$itemsByColumn == ($itemsByColumn-1)) {
                                            $htmlContent .= '</ul></div>';
                                            $openBox = false;
                                        }
                                        $rint ++;
                                    }
                                    if($openBox) {
                                        $htmlContent .= '</ul></div>';
                                    }

                                    $htmlTabs .= '<div class="tab-pane fade" id="'.$taxname.'" role="tabpanel" aria-labelledby="'.$taxname.'-tab">
                                                    <div class="row mx-0">
                                                    '.$htmlContent.'
                                                    </div>
                                                  </div>';
                                    $htmlMobile .= '
                                        <div class="accordion-box">
                                            <div class="accordion-box-header white" id="heading'.$taxname.'">
                                                <button class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#collapse'.$taxname.'" aria-expanded="false" aria-controls="collapse'.$taxname.'">'.ucfirst($taxname).'</button>
                                            </div>

                                            <div id="collapse'.$taxname.'" class="collapse" aria-labelledby="heading'.$taxname.'" data-parent="#'.$defaultAcclug.'">
                                                <div class="accordion-box-body"><div class="row">'.$htmlContent.'</div></div>
                                            </div>
                                        </div>';

                                }			
                                $htmlLabels .= '
                                    <li class="nav-item">
                                        <a class="nav-link" id="date-tab" data-toggle="tab" href="#date" role="tab" aria-controls="date" aria-selected="false">By Date</a>
                                    </li>';

                                    $htmlContent = '
                                    <div class="'.$columClass.'"><ul class="filter-results">
                                        <li>
                                            <label><input type="radio" id="all-dates" name="date" value="null" v-on:click="filterTaxonomy()"> All dates</label>
                                        </li>';
                                    $cstudies_dates = $wpdb->get_results( $wpdb->prepare( "
                                        SELECT
                                            DATE_FORMAT(`post_date`,'%Y%m')  AS date,
                                            YEAR( post_date )  AS year,
                                            MONTHNAME(post_date) AS monthname
                                        FROM {$wpdb->posts}
                                        WHERE
                                            post_type = 'casestudy'
                                        AND 
                                            post_status = 'publish'
                                        GROUP BY
                                            date
                                        ORDER BY post_date
                                        DESC
                                    "));
                                    //error_log(print_r($cstudies_dates,true));
                                    $rint    = 1;
                                    $openBox = true;
                                    foreach ($cstudies_dates as $cstudies_date) {
                                        if($rint%$itemsByColumn == 0) {
                                            $htmlContent .= '<div class="'.$columClass.'"><ul class="filter-results">';
                                            $openBox = true;
                                        }
                                        $htmlContent .= '
                                        <li>
                                            <label><input type="radio" id="'.$cstudies_date->date.'" name="date" value="'.$cstudies_date->date.'" v-on:click="filterTaxonomy()">
                                            '.$cstudies_date->monthname.' ('.$cstudies_date->year.')</label>
                                        </li>';
                                        if($rint%$itemsByColumn == ($itemsByColumn-1)) {
                                            $htmlContent .= '</ul></div>';
                                            $openBox = false;
                                        }
                                        $rint ++;
                                    }
                                    if($openBox) {
                                        $htmlContent .= '</ul></div>';
                                    }
                                    $htmlTabs .= '<div class="tab-pane fade " id="date" role="tabpanel" aria-labelledby="date-tab">
                                                    <div class="row mx-0">
                                                    '.$htmlContent.'
                                                    </div>
                                                  </div>';

                                    $htmlMobile .= '
                                        <div class="accordion-box">
                                            <div class="accordion-box-header white" id="headingdate">
                                                <button class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#collapsedate" aria-expanded="false" aria-controls="collapsedate">Date</button>
                                            </div>

                                            <div id="collapsedate" class="collapse" aria-labelledby="headingdate" data-parent="#'.$defaultAcclug.'">
                                                <div class="accordion-box-body"><div class="row">'.$htmlContent.'</div></div>
                                            </div>
                                        </div>';

                                    echo '
                                       <div class="col-md-4 col-xl-3 gblock__tabs--labels p-0 white">
                                            <div id="'.$defaultAcclug.'" class="d-md-none accordion">
                                                '.$htmlMobile.'
                                            </div>
                                            <ul class="nav nav-tabs d-none d-md-block" role="tablist">
                                                '.$htmlLabels.'
                                            </ul>
                                        </div>
                                        <div class="col-md-8 col-xl-9 p-0 d-none d-md-block">
                                            <div class="tab-content">
                                                '.$htmlTabs.'
                                            </div>
                                        </div>
                                    ';
                                ?>
                            </div>
                        </script>

                        



                        <script type="text/html" id="cstudies-list-tmpl">

                            <div id="cstudies" >


                                <div v-if="loading" class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- section -->
                                            <section class="hv-50">
                                                <!-- article -->
                                                <div class="loading-container text-center mt-5">
                                                    <img v-bind:src="loading_gif" />
                                                </div>
                                                <!-- /article -->
                                            </section>
                                            <!-- /section -->
                                        </div><!-- /.col-12 -->
                                    </div><!-- /.row -->
                                </div><!-- /.container -->


                                <div v-if="empty" class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- section -->
                                            <section class="hv-50">
                                                <!-- article -->
                                                <article id="post-404">
                                                    <h2 class="text-center mt-5"><?php _e( 'No Results', 'codigo' ); ?></h2>
                                                </article>
                                                <!-- /article -->
                                            </section>
                                            <!-- /section -->
                                        </div><!-- /.col-12 -->
                                    </div><!-- /.row -->
                                </div><!-- /.container -->


                                <div v-if="loading===false" class="row no-gutters">


                                    <article v-for="(cstudy, key) in cstudies" v-if="key<3" v-bind:id="'post-' + cstudy.id" v-bind:class="'col-md-12 px-0 loop-largepost ' + cstudy.class">

                                        <section id="" class="section d-flex hv-100" v-bind:style="cstudy.sectionstyle">

                                            <div v-if="cstudy.parallax" class="rellax fp-bg fp-bg--image">
                                                <figure v-html="cstudy.post_image"></figure>
                                            </div>


                                            <div class="gblock__headline--wrapper container-fluid  mt-auto mb-5  d-flex flex-column">
                                            
                                                <div class="row gblock__headline_body--text">
                                                    <div v-bind:class="'gblock__headline_body--content hv-50 d-flex flex-column col-lg-6 '+ cstudy.innerClass">
                                                        <h3 class="loop-title mb-4"><a v-bind:href="cstudy.permalink"  v-bind:title="cstudy.title" v-bind:style="cstudy.title_style" v-html="cstudy.title"></a></h3>
                                                        <div class="loop-excerpt" v-html="cstudy.excerpt"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                    </article>

                                    <article v-for="(cstudy, key) in cstudies" v-if="key>2 && key<7" v-bind:id="'post-' + cstudy.id" v-bind:class="'col-md-12 px-0 loop-post loop-bigpost ' + cstudy.class">

                                        <div class="row no-gutters">
                                            <div class="col-lg-6 d-flex flex-column loop-content">
                                                <h3 class="loop-title mb-4"><a v-bind:href="cstudy.permalink" v-bind:title="cstudy.title" v-html="cstudy.title"></a></h3>
                                                <div v-html="cstudy.excerpt"></div>
                                            </div>
                                            <div v-bind:class="'col-lg-6 '+ cstudy.innerClass">
                                                <!-- post thumbnail -->
                                                <a v-bind:href="cstudy.permalink" v-bind:title="cstudy.title">
                                                    <figure v-html="cstudy.post_image"></figure>
                                                </a>
                                                <!-- /post thumbnail -->
                                            </div>
                                        </div>
                                    </article>
                                
                                </div>

                                <div v-if="loading===false" class="row no-gutters">

                                    <article v-for="(cstudy, key) in cstudies" v-if="key>6" v-bind:id="'post-' + cstudy.id" v-bind:class="'col-md-6 px-0 loop-post loop-smallpost ' + cstudy.class">
                                        <div class="d-flex flex-column loop-content">
                                            <h3 class="loop-title my-auto"><a v-bind:href="cstudy.permalink" v-bind:title="cstudy.title" v-html="cstudy.title"></a></h3>
                                        </div>
                                    </article>

                                </div>


                            </div>
                        </script>




			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>

<script>
(function($){
	//console.log('Starting VUE...');
    

	const eventBus = new Vue();

	//Add to the DOM a variable called config with the API URL and the REST API nonce.
	var config = {
        api: {
            cstudy: "<?php echo esc_url_raw( rest_url( 'wp/v1/case-study' ) ); ?>"
        },
        nonce: "<?php echo wp_create_nonce( 'wp_rest' ); ?>"
    };

    var cstudy = Vue.component('cstudies-list', {
        template: '#cstudies-list-tmpl',
        data: function() {
            return {
            	loading: 	    false,
            	loading_gif:    '<?=get_stylesheet_directory_uri()?>/vue/img/loading.gif',
            	empty: 		    false,
            	solution: 	    null,
                region: 	    null,
                sector: 	    null,
                date: 	 	    null,
                windowresizing: false,
                cstudies: 	    []
            }
        },
        created() {
		    eventBus.$on('fireTaxonomy', () => {
		            this.filterTaxonomy();
		    });
		    eventBus.$on('resetAll', () => {
		            this.resetAll();
		    });
            window.addEventListener("resize", this.myEventHandler);
		},
        destroyed() {
        window.removeEventListener("resize", this.myEventHandler);
        },
        mounted: function () {
        	this.filterCStudies();            
        },
        methods: {
            filterCStudies: function () {
	            var self = this;
	            var getURL = config.api.cstudy + '/solution=' + self.solution + '/region=' + self.region + '/sector=' + self.sector + '/date=' + self.date;

	            self.loading = true;
	            //console.log('solution = '+ self.solution);
	            //console.log('loading '+ getURL);

	            $.get( getURL , function( r ){
	            	self.loading  = false;
	            	self.cstudies = r;

                    self.cstudies.map((cstudy, key) => { 
                        //console.log(key);
                        if(key < 3 ) {
                            
                            if(<?=$parallax?>) {
                                cstudy.parallax = true;
                                cstudy.sectionstyle = 'overflow:hidden';
                            } else {
                                cstudy.sectionstyle = 'background-image: url('+cstudy.post_image_url+');background-size: cover;';
                            }
                            //console.log('cstudy.parallax '+cstudy.parallax);
                            //console.log('cstudy.sectionstyle '+cstudy.sectionstyle);


                            if(key%2 === 1 ) {
                                cstudy.innerClass = 'offset-lg-6';
                            }
                        } else {
                            if(key < 7 ) {
                                let lpost = key - 2
                                cstudy.class = cstudy.class + ' loop-bigpost-'+lpost;
                                if(key%2 === 0 ) {
                                    cstudy.innerClass  = 'order-lg-first';
                                }
                            }
                        } 
                        //console.log('innerClass '+cstudy.innerClass);
                    });


	            }).done(function() {
                    setTimeout(() => {
                        //console.log( "clicking" );
                        $('.vue_lodaded').trigger('click');
                    }, 500)
                    
                }).fail(function() {
				    //console.log('woops');
				    self.loading  = false;
	            	self.cstudies = [];
	            	self.empty    = true;
				});

                

	        },
	        filterTaxonomy: function(){
                let solutions='',regions='',sectors='',date;
                $('#appTaxonomies input:checked').each(function() {
                    if($(this).parents('#solution').length) {
                        solutions +=  $(this).attr('value') +',';
                    }
                    if($(this).parents('#collapsesolution').length) {
                        solutions +=  $(this).attr('value') +',';
                    }

                    if($(this).parents('#region').length) {
                        regions +=  $(this).attr('value') +',';
                    }
                    if($(this).parents('#collapseregion').length) {
                        regions +=  $(this).attr('value') +',';
                    }

                    if($(this).parents('#sector').length) {
                        sectors +=  $(this).attr('value') +',';
                    }
                    if($(this).parents('#collapsesector').length) {
                        sectors +=  $(this).attr('value') +',';
                    }

                    if($(this).parents('#date').length) {
                        date =  $(this).attr('value');
                    }
                    if($(this).parents('#collapsedate').length) {
                        date =  $(this).attr('value');
                    }
                });

                if(solutions) {
                    solutions = solutions.slice(0, -1);
                    this.solution = solutions;
                    //console.log('Filter Solution by '+solutions);
                }
                if(regions) {
                    regions = regions.slice(0, -1);
                    this.region = regions;
                    //console.log('Filter Region by '+regions);
                }
                if(sectors) {
                    sectors = sectors.slice(0, -1);
                    this.sector = sectors;
                    //console.log('Filter Sector by '+sectors);
                }
                if(date) {
                    this.date = date;
                    //console.log('Filter Date by '+date);
                }
                
                this.empty = false;

                this.filterCStudies();
            },
            resetAll: function(){
                console.log('Reset');
                $('#appTaxonomies input').prop('checked', false);
                this.solution =  null;
                this.region   =  null;
                this.sector   =  null;
                this.date     =  null;
                this.empty    = false;
                this.filterCStudies();
            },
            myEventHandler(e) {
                // your code for handling resize...
                if(!this.windowresizing) {
                    this.resetAll();
                    this.windowresizing = true;
                    setTimeout(() => {
                        this.windowresizing = false;
                    }, 1000)
                }
            }
        }
    });


    var fRole = Vue.component('filter-taxonomies', {
        template: '#filter-taxonomies-tmpl',
        methods: {
            filterTaxonomy: function(){
                //console.log('filterTaxonomy ');
                eventBus.$emit('fireTaxonomy');
            }
        }
    });


    var tButtons = Vue.component('trigger-buttons', {
        template: '#trigger-buttons-tmpl',
        methods: {
            resetAll: function(){
                //console.log('button Reset');
                eventBus.$emit('resetAll');
            }
        }
    });


    new Vue({cstudy}).$mount('#appCStudies')
    new Vue({fRole}).$mount('#appTaxonomies')
    new Vue({tButtons}).$mount('#appButtons')


    //CDG.restApiFilters() manages the Filters Events


})( jQuery );
</script>

<?php get_footer(); ?>
