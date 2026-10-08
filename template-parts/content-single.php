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

$comments_number = get_comments_number();
$comments_text   = sprintf( '(%s)', number_format_i18n( $comments_number ) );
$has_entry_meta  = RDTheme::$options['post_author_name'] || RDTheme::$options['post_comment_num'] || RDTheme::$options['post_date'] ? true : false;
$footer_class    = RDTheme::$options['post_tag'] && has_tag() && RDTheme::$options['post_social_icon'] && class_exists( 'ClProperty_Core' ) ? 'col-md-6 col-sm-12 col-12'
	: 'col-md-12 col-sm-12 col-12';
$has_post_footer = ( RDTheme::$options['post_tag'] && has_tag() ) || ( RDTheme::$options['post_social_icon'] && class_exists( 'ClProperty_Core' ) ) ? true : false;
$has_post_social = ( class_exists( 'ClProperty_Core' ) && RDTheme::$options['post_social_icon'] );

$author=$post->post_author;
$news_author_fb = get_user_meta( $author, 'rt_facebook', true );
$news_author_tw = get_user_meta( $author, 'rt_twitter', true );
$news_author_ld = get_user_meta( $author, 'rt_linkedin', true );
$news_author_pr = get_user_meta( $author, 'rt_pinterest', true );
$news_author_ins = get_user_meta( $author, 'rt_instagram', true );
$news_author_vimeo = get_user_meta( $author, 'rt_vimeo', true );
$news_author_youtube = get_user_meta( $author, 'rt_youtube', true );
$author_social= $news_author_fb || $news_author_tw || $news_author_ld || $news_author_pr || $news_author_ins || $news_author_vimeo || $news_author_youtube ? true: false;

?>
<div class="single-blog-content block-content">
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'post-each post-each-single' ); ?>>

		<?php if ( has_post_thumbnail() ): ?>
            <div class="blog-img">
				<?php the_post_thumbnail(); ?>
				<?php edit_post_link( 'Edit' ); ?>
            </div>
		<?php endif; ?>

        <div class="blog-content">
            <?php if ( RDTheme::$options['blog_cat_visibility'] && has_category() ): ?>
                <div class="category">
                    
                    <?php echo wp_kses_post( get_the_category_list() );
                    ?>
                    
                </div>
			<?php endif; ?>
            <div class='post-title-wrap'><h2 class="post-title"><?php the_title(); ?></h2></div>
			<?php if ( $has_entry_meta ): ?>
                <div class="post-meta rt-theme-post-meta">
                    <ul class="entry-meta">
						<?php if ( RDTheme::$options['post_author_name'] ): ?>
                            <li>
                                <i class="icon-rt-icon-user-line"></i><span class="vcard author"><?php echo esc_html__( 'by', 'clproperty' ); ?>
                                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author(); ?></a>
                                </span>
                            </li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_date'] ): ?>
                            <li><i class="fas fa-calendar-alt"></i><span class="updated published"><?php the_time( get_option( 'date_format' ) ); ?></span></li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_cats'] && has_category() ): ?>
                            <li><i class="fa-solid fa-tag"></i><?php the_category( ', ' ); ?></li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_comment_num'] ): ?>
                            <li><i class="far fa-comments" aria-hidden="true"></i><?php echo esc_html( $comments_text ); ?></li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_details_reading_time'] ): ?>
                            <li>
                            <i class="fa-solid fa-clock"></i><span data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr__( 'Reading Time', 'clproperty' ) ?>"
                                      data-original-title="<?php echo esc_attr__( 'Reading Time', 'clproperty' ) ?>"><?php echo esc_html( Helper::reading_time_count( get_the_content(), true ) ); ?>
                                </span>
                            </li>
						<?php endif; ?>
                    </ul>
                </div>
			<?php endif; ?>

            
            <div class="post-details clearfix"><?php the_content(); ?></div>
			<?php wp_link_pages(); ?>
        </div>
    </div>
</div>
<?php if ( $has_post_footer ): ?>
    <div class="social-share <?php echo esc_attr( $has_post_social ? '' : 'has-no-share' ) ?>">
        <div class="row align-items-center">
            <?php if ( class_exists( 'ClProperty_Core' ) && RDTheme::$options['post_social_icon'] ): ?>
                <div class="<?php echo esc_attr( $footer_class ); ?>">
                    <div class="post-social-share-inner">
                        <h4 class="social-label">
                            <?php  esc_html_e( 'Share This Post:', 'clproperty' ); ?>
                        </h4>
                        <?php \ClProperty_Core::social_share( Helper::post_share_on_social() ); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( has_tag() && RDTheme::$options['post_tag'] ): ?>
                <div class="<?php echo esc_attr( $footer_class ); ?>">
                    <div class="item-tag">
                        <h4><?php esc_html_e( 'Tags:', 'clproperty' ); ?></h4>
                        <?php echo get_the_term_list( $post->ID, 'post_tag', '', ', ' ); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php
if ( RDTheme::$options['post_navigation'] ) {
	get_template_part( 'template-parts/content-single-pagination' );
}
?>
<?php if ( RDTheme::$options['post_author_about'] ): ?>
    <div class="blog-author">
        <div class="widget-box">
            <div class="media">
                
                <div class="item-img">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 110 ); ?>
                </div>
                
                <div class="media-body">
                    <div class="heading-part">
                        <div class="title">
                            <h3 class="item-title"><?php the_author_posts_link(); ?></h3>
                            <?php 
                            $user_info = get_userdata($author);
                            ?>
                            <span><?php echo esc_html(implode( ', ', $user_info->roles )); ?></span>
                        </div>
                        <?php if($author_social){ ?>
                            <ul class="author-social">
                                <?php if(!empty($news_author_fb)){ ?>
                                    <li class="fb"><a href="<?php echo esc_url($news_author_fb); ?>"><i class="icon-rt-icon-facebook-solid"></i></a></li>
                                <?php } ?>
                                <?php if(!empty($news_author_tw)){ ?>
                                    <li class="tw"><a href="<?php echo esc_url($news_author_tw); ?>"><i class="icon-rt-icon-twitter-solid"></i></a></li>
                                <?php } ?>
                                <?php if(!empty($news_author_ld)){ ?>
                                    <li class="ld"><a href="<?php echo esc_url($news_author_ld); ?>"><i class="fab fa-linkedin-in"></i></a></li>
                                <?php } ?>
                                <?php if(!empty($news_author_pr)){ ?>
                                    <li class="pr"><a href="<?php echo esc_url($news_author_pr); ?>"><i class="fab fa-pinterest"></i></a></li></a></li>
                                <?php } ?>
                                <?php if(!empty($news_author_youtube)){ ?>
                                    <li class="you"><a href="<?php echo esc_url($news_author_youtube); ?>"><i class="icon-rt-icon-youtube-solid"></i></a></li></a></li>
                                <?php } ?>
                                <?php if(!empty($news_author_ins)){ ?>
                                    <li class="ins"><a href="<?php echo esc_url($news_author_ins); ?>"><i class="icon-rt-icon-instagram-solid"></i></a></li></a></li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


