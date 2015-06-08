<?php
/**
 * Tags de modelo personalizadas para este tema
 * 
 * Eventualmente, algumas das funcionalidades aqui poderia ser substituída
 * por características do wordpress
 * 
 * @package IS Simple
 * @since 1.0
 */


/**
 * Favicon personalizado
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function my_favicon(){
	$favicon 			= ICONS_URI . '/favicon.ico';
	$apple_icons 		= issimple_readdir( ICONS_PATH, 'png' );
	$apple_icons_name 	= array_keys( $apple_icons );
	$apple_icons_count 	= count( $apple_icons_name );
	$apple_icons_size 	= str_replace( '-', '', $apple_icons_name);
	$apple_icons_size 	= str_replace( 'appletouchicon', '', $apple_icons_size);
	
	$favicons  = '<!-- Favicon IE 9 -->';
	$favicons .= '<!--[if lte IE 9]><link rel="icon" type="image/x-icon" href="' . $favicon . '" /> <![endif]-->';
	
	$favicons .= '<!-- Favicon Outros Navegadores -->';
	$favicons .= '<link rel="shortcut icon" type="image/png" href="' . $favicon . '" />';
	
	$favicons .='<!-- Favicon Apple -->';
	
	for ( $i = 0; $i < $apple_icons_count; $i++ ) :
		$size = ( $apple_icons_size[$i] == '' ) ? '' : ' sizes="' . $apple_icons_size[$i] . '"';
		
		$favicons .='<link rel="apple-touch-icon"' . $size . ' href="' . ICONS_URI . '/' . $apple_icons_name[$i] . '.png" />';
	endfor;
	
	echo $favicons;
}
//add_action( 'wp_head', 'my_favicon' );
//add_action( 'admin_head', 'my_favicon' );
//add_action( 'login_head', 'my_favicon' );


/**
 * Ícone personalizado para a tela de login
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_login_icon(){
	$login_icon_url    = IMAGES_URI . '/issimple-logo.svg';
	$login_icon_width  = 100;
	$login_icon_height = 100;
	
	$output  = '
		<style id="issimple_login_icon" type="text/css">
			.login h1 a {
				background-image: url( "' . $login_icon_url . '" );
				background-size: ' . $login_icon_width . 'px auto;
				width: ' . $login_icon_width . 'px;
				height: ' . $login_icon_height . 'px;
			}
		</style>
	';
	
	echo $output;
}
add_action( 'login_enqueue_scripts', 'issimple_login_icon' );


/**
 * Título das páginas
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function my_wp_title( $title, $sep ) {
	$site_name = get_bloginfo( 'name', 'display' );
	$site_description = get_bloginfo( 'description', 'display' );
	
	if ( is_page() || is_archive() || is_single() ) $title .= ' - ' . $site_description;
	
	return str_replace( "$site_name $sep $site_description", "$site_name - $site_description", $title );
}
add_filter( 'wp_title', 'my_wp_title', 10, 2 );


/**
 * Adiciona o nome da página como classe no elemento <body>
 * Créditos: Starkers Wordpress Theme
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function add_name_to_body_class( $classes ) {
	global $post;
	
	if ( is_home() || is_page( 'home' ) ) {
		$key = array_search( 'blog', $classes );
		if ( $key > -1 ) unset( $classes[$key] );
	} elseif ( is_page() || is_singular() ) {
		$classes[] = sanitize_html_class( $post->post_name );
	}
	
	return $classes;
}
add_filter( 'body_class', 'add_name_to_body_class' );


/**
 * Adiciona o atributo 'role' aos menus de navegação
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function add_role_navigation_to_nav_menu( $nav_menu, $args ) {
	if( 'nav' != $args->container ) return $nav_menu;
	
	return str_replace( '<'. $args->container, '<'. $args->container . ' role="navigation"', $nav_menu );
}
add_filter( 'wp_nav_menu', 'add_role_navigation_to_nav_menu', 10, 2 );


/**
 * Display a search form with custom attributes "id" and "class"
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_content_search_form( $form_id = false, $form_class = false ) {
	$search_form_id = ( false !== $form_id ) ? $form_id . '-search-form' : 'search-form';
	$search_form_class = ( false !== $form_class ) ? ' class="' . $form_class . '"' : '';
	?>
	
	<form id="<?php echo $search_form_id; ?>"<?php echo $search_form_class; ?> method="get" action="<?php echo home_url( '/' ); ?>" role="search">
		<div class="form-group">
			<label for="s" class="control-label sr-only"><?php _e( 'Search', 'issimple' ); ?></label>
			<div class="input-group">
				<input class="form-control" type="search" name="s" placeholder="<?php _e( 'Search', 'issimple' ); ?>">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" role="button"><span class="sr-only"><?php _e( 'Search', 'issimple' ); ?></span> <i class="fa fa-search"></i></button>
				</span>
			</div>
		</div>
	</form><!-- #<?php echo $search_form_id; ?> -->
	
	<?php
}


/**
 * Primary class based on page template
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_primary_class() {
	if ( is_page_template( 'full-width.php' ) ) :
		echo 'col-sm-12 col-md-12';
	else:
		echo 'col-sm-8 col-md-8';
	endif;
}

/**
 * Títulos personalizados para páginas arquivos
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function my_archive_title( $title ) {
	if ( is_category() ) :
		$title = sprintf( __( 'Posts in category: %s', 'issimple' ), single_cat_title( '', false ) );
	elseif ( is_tag() ) :
		$title = sprintf( __( 'Posts in tag: %s', 'issimple' ), single_tag_title( '', false ) );
	elseif ( is_author() ) :
		$title = sprintf( __( 'Posts of the author: %s', 'issimple' ), get_the_author() );
	elseif ( is_day() ) :
		$title = sprintf( __( 'Posts of the day: %s', 'issimple' ), get_the_date( get_option( 'date_format' ) ) );
	elseif ( is_month() ) :
		$title = sprintf( __( 'Posts of the month: %s', 'issimple' ), get_the_date( 'F \/ Y' ) );
	elseif ( is_year() ) :
		$title = sprintf( __( 'Posts of the year: %s', 'issimple' ), get_the_date( 'Y' ) );
	endif;
	
	return $title;
}
add_filter( 'get_the_archive_title', 'my_archive_title' );


/**
 * Paginação de Artigos
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_post_pagination() {
	issimple_posts_pagination( array(
		'screen_reader_text' => __( 'Posts navigation', 'issimple' ),
		'prev_text'          => '<i class="fa fa-arrow-left"></i> ' . '<span class="meta-nav sr-only">' . __( 'Previous page', 'issimple' ) . ' </span>',
		'next_text'          => '<span class="meta-nav sr-only">' . __( 'Next page', 'issimple' ) . ' </span>' . ' <i class="fa fa-arrow-right"></i>',
		'before_page_number' => '<span class="meta-nav sr-only">' . __( 'Page', 'issimple' ) . ' </span>',
		'type'				 => 'list'
	) );
	
	echo '<!-- .pagination -->';
}


function issimple_posts_pagination( $args = array() ) {
	$navigation = '';
	
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
		$args = wp_parse_args( $args, array(
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous' ),
			'next_text'          => __( 'Next' ),
			'screen_reader_text' => __( 'Posts navigation' ),
		) );
		
		// Make sure we get a string back. Plain is the next best thing.
		if ( isset( $args['type'] ) && 'array' == $args['type'] ) {
			$args['type'] = 'plain';
		}
		
		// Set up paginated links.
		$links = paginate_links( $args );
		
		if ( $links ) {
			$navigation = issimple_navigation_markup( $links, 'pagination', $args['screen_reader_text'] );
		}
		
	}
	
	echo $navigation;
}

function issimple_navigation_markup( $links, $class = 'posts-navigation', $screen_reader_text = '' ) {
	if ( empty( $screen_reader_text ) ) {
		$screen_reader_text = __( 'Posts navigation' );
	}
	
	$template = '
	<nav class="navigation %1$s" role="navigation">
		<h2 class="sr-only">%2$s</h2>
		<div class="nav-links">%3$s</div>
	</nav>';
	
	return sprintf( $template, sanitize_html_class( $class ), esc_html( $screen_reader_text ), $links );
}


/**
 * Coleta informações da imagem destacada da postagem
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_get_thumb_meta( $thumbnail_id, $meta ) {
	$thumb = get_post( $thumbnail_id );
	
	$thumb = array(
		'alt'			=> get_post_meta( $thumb->ID, '_wp_attachment_image_alt', true ),
		'caption'		=> $thumb->post_excerpt,
		'description'	=> $thumb->post_content,
		'href'			=> get_permalink( $thumb->ID ),
		'src'			=> $thumb->guid,
		'title'			=> $thumb->post_title
	);
	
	return $thumb[$meta];
}


/**
 * Miniaturas personalizadas para as postagens
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_post_featured_thumb( $size = 'featured-size' ) {
	$thumb_id = get_post_thumbnail_id();
	
	$thumb_link_full = wp_get_attachment_image_src( $thumb_id, 'full' );
	$thumb_link_full = $thumb_link_full[0];
	
	$thumb_caption = issimple_get_thumb_meta( $thumb_id, 'caption' );
	
	if ( has_post_thumbnail() ) :
		?>
		
		<figure class="post-featured-thumb">
			<a class="featured-link img-link"
			   href="<?php if ( is_single() ) : echo $thumb_link_full; else : the_permalink(); endif; ?>"
			   title="<?php the_title(); ?>"
			   <?php if ( is_single() ) : ?>data-lightbox="post-<?php the_ID(); ?>" data-title="<?php echo $thumb_caption; ?>"<?php endif; ?>>
				<?php the_post_thumbnail( $size, array( 'class' => 'featured-img img-thumbnail', 'alt' => get_the_title() ) ); ?>
			</a>
		</figure><!-- .post-featured-thumb -->
		
		<?php
	endif;
}


/**
 * Detalhes personalizadas para as postagens
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_entry_meta() {
	if ( 'post' == get_post_type() ) :
		?>
		<p class="entry-meta bg-info">
			<span class="entry-author"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
			<span class="entry-categ"><i class="fa fa-folder-open"></i> <?php the_category( ', ' ); ?></span> 
			<span class="entry-date"><i class="fa fa-clock-o"></i> <?php issimple_date_link(); ?></span>
			<span class="entry-comments"><i class="fa fa-comments"></i> <?php issimple_comment_link(); ?></span>
			<?php edit_post_link( __( 'Edit', 'issimple' ), '<span class="edit-link"><i class="fa fa-pencil"></i> ', '</span>' ); ?>
		</p><!-- .entry-meta -->
		<?php
	endif;
}


/**
 * Cria datas como links
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_date_link() {
	$year		= get_the_time( 'Y' );
	
	$month		= get_the_time( 'm' );
	$month_name	= get_the_time( 'F' );
	$month_date	= get_the_time( 'F \/ Y' );
	
	$day		= get_the_time( 'd' );
	$day_date	= get_the_time( get_option( 'date_format' ) );
	
	$time_title	= get_the_time( 'l, ' . get_option( 'date_format' ) . ', h:ia' );
	$time_datetime	= esc_attr( get_the_date( 'c' ) );
	
	$day_link	= '<a href="' . get_day_link( $year, $month, $day ) . '" title="' . sprintf( __( 'Posts of %s', 'issimple' ), $day_date ) . '">' . $day . '</a>';
	$month_link	= '<a href="' . get_month_link( $year, $month ) . '" title="' . sprintf( __( 'Posts of %s', 'issimple' ), $month_date ) . '">' . $month_name . '</a>';
	$year_link	= '<a href="' . get_year_link( $year ) . '" title="' . sprintf( __( 'Posts of %s', 'issimple' ), $year ) . '">' . $year . '</a>';
	
	
	
	$output  = sprintf( '<time class="date" title="%s" datetime="%s">' , $time_title, $time_datetime );
	$output .= sprintf( __( '%s of %s of %s', 'issimple' ), $day_link, $month_link, $year_link );
	$output .= '</time>';
	
	echo $output;
}


/**
 * Link para os comentários
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_comment_link() {
	if ( comments_open( get_the_ID() ) )
		comments_popup_link(
			__( 'Leave your thoughts', 'issimple' ),
			__( '1 comment', 'issimple' ),
			__( '% comments', 'issimple' )
		);
}


/**
 * Criar resumos personalizados
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_excerpt( $length_callback = '', $more_callback = '' ) {
	global $post;
	
    if ( function_exists( $length_callback ) ) {
    	add_filter( 'excerpt_length', $length_callback );
	}
	
	if ( function_exists( $more_callback ) ) {
		add_filter( 'excerpt_more', $more_callback );
	}
	
	the_excerpt();
}


/**
 * Tamanho em palavras para os resumos personalizados.
 * Uso: issimple_excerpt( 'issimple_index' );
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_index( $length ) {
	return 50;
}


/**
 * Tamanho em palavras para os resumos personalizados do slider.
 * Uso: issimple_excerpt( 'issimple_length_slider' );
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_length_slider( $lenght ) {
	return 10;
}


/**
 * Cria link Ver Artigo personalizado para a postagem
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_read_more( $more ) {
	global $post;
	
	$tagmore  = '...</p><p class="view-article">';
	$tagmore .= '<a class="btn btn-info" ';
	$tagmore .= 'href="' . get_permalink( $post->ID ) . '" ';
	$tagmore .= 'title ="' . __( 'View post:', 'issimple' ) . ' ' . get_the_title() . '">';
	$tagmore .= __( 'View post', 'issimple' );
	$tagmore .= '</a>';
	
	return $tagmore;
}
add_filter( 'excerpt_more', 'issimple_read_more' );


/**
 * Navegação dos comentários
 * 
 * @since IS Simple 1.0
 * ----------------------------------------------------------------------------
 */
function issimple_comment_nav() {
	// Há comentários para navegação?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav class="nav comment-nav" role="navigation">
			<h2 class="sr-only"><?php _e( 'Comment navigation', 'issimple' ); ?></h2>
			<div class="nav-links">
				<?php
					if ( $prev_link = get_previous_comments_link( __( 'Older comments', 'issimple' ) ) ) :
						printf( '<div class="nav-previous">%s</div>', $prev_link );
					endif;
	
					if ( $next_link = get_next_comments_link( __( 'Newer comments', 'issimple' ) ) ) :
						printf( '<div class="nav-next">%s</div>', $next_link );
					endif;
				?>
			</div><!-- .nav-links -->
		</nav><!-- .comment-nav -->
	<?php endif;
}




