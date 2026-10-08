<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
	<?php get_header(); ?>
    <main class="site-main content-area ptb-100 clproperty-myaccount">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-12">
					<?php while ( have_posts() ) : the_post(); ?>
                        <div class="page-content-block">
                            <div class="main-content">
                                <div class="clearfix">
                                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php if ( has_post_thumbnail() ): ?>
                                            <div class="main-thumbnail"><?php the_post_thumbnail(); ?></div>
										<?php endif; ?>
										<?php the_content(); ?>
                                    </div>
                                </div>
                                <div class="page-pagination">
									<?php wp_link_pages(); ?>
                                </div>
                            </div>
                        </div>
					<?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>
	<?php get_footer(); ?>