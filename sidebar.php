<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage IS Simple
 * @since IS Simple 1.0
 */
?>

<section id="sidebar" class="col_4" role="complementary">
	<div id="sidebar-content">
		<div id="nav-header">
			<?php
				if ( has_nav_menu( 'header-menu' ) ) :
				wp_nav_menu( array(
					'theme_location'	=> 'header-menu',
					'container'			=> 'nav',
					'container_id'		=> 'header-menu-nav',
					'menu_id'			=> 'header-menu'
				) );
				echo '<!-- #header-menu-nav -->';
				endif;
				
				if ( has_nav_menu( 'social-menu' ) ) :
					wp_nav_menu( array(
						'theme_location'	=> 'social-menu',
						'container'			=> 'nav',
						'container_id'		=> 'social-menu-nav',
						'menu_id'			=> 'social-menu',
						'menu_class'		=> 'nav-menu',
						'depth'				=> 1,
						'link_before'		=> '<span class="screen-reader-text">',
						'link_after'		=> '</span>'
					) );
					echo '<!-- #social-menu-nav -->';
				endif;
				
				//get_lang_switcher();
				
				get_search_form();
			?>
		</div><!-- #nav-header -->
		
		<?php if ( is_active_sidebar( 'widget-area' ) ) : ?>
			<div class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'widget-area' ); ?>
			</div><!-- .widget-area -->
		<?php endif; ?>
	</div>
</section><!-- .sidebar -->