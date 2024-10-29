<?php
/*
Plugin Name: Alpha's Categories Widget
Description: Enhanced Categories Widget,Compatible up to 2.8.
Author: Alpha.M.
Version: 0.2.1
Author URI: http://blog.efrees.com
Plugin URI: http://blog.efrees.com/alpha-s-categories-widget-for-wordpress.html
*/
class WP_Widget_Alpha_Categories extends WP_Widget {

	function WP_Widget_Alpha_Categories() {
		$widget_ops = array( 'classname' => 'widget_alpha_categories', 'description' => __( "A list or dropdown of categories" ) );
		$this->WP_Widget('alpha_categories', __('Alpha Categories'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title']);

		if($instance['child_of'])
			unset($instance['show_option_all']);
		elseif($instance['show_option_all'])
			$instance['show_option_all']=__('All',true);
			
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		if ($instance['dropdown']) {
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $instance));
?>

<script type='text/javascript'>
/* <![CDATA[ */
	var dropdown = document.getElementById("cat");
	function onCatChange() {
		var cat_id=dropdown.options[dropdown.selectedIndex].value
		if ( cat_id > 0 ) {
			location.href = "<?php echo get_option('home'); ?>/?cat=" + cat_id;
		}else{
			location.href = "<?php echo get_option('home'); ?>/";
		}
	}
	dropdown.onchange = onCatChange;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$instance['title_li'] = '';
		wp_alpha_list_categories(apply_filters('widget_categories_args', $instance));
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$new_instance['title'] = strip_tags($new_instance['title']);
		$boolfields=array(
			'show_count','hide_empty','dropdown','show_option_all','hierarchical',
			'use_desc_for_title',
		);
		foreach($boolfields as $f){
			$new_instance[$f] = isset($new_instance[$f]) ? 1 : 0;
		}
		$instance=wp_parse_args($new_instance,$old_instance);
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$defaults = array(
			'show_option_all' => '', 'orderby' => 'name',
			'order' => 'ASC', 'show_last_update' => 0,
			'style' => 'list', 'show_count' => 0,
			'hide_empty' => 1, 'use_desc_for_title' => 1,
			'child_of' => 0, 'feed' => '', 'feed_type' => '',
			'feed_image' => '', 'exclude' => '', 'exclude_tree' => '', 'current_category' => 0,
			'hierarchical' => true, 'title' => '',
			'depth' => 0,'dropdown'=>0
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		extract($instance);
		$title = esc_attr( $title );


?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('child_of'); ?>"><?php _e( 'Child of:' ); ?></label>
		<input size="3" id="<?php echo $this->get_field_id('child_of'); ?>" name="<?php echo $this->get_field_name('child_of'); ?>" type="text" value="<?php echo $child_of; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label>
		<input id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('include'); ?>"><?php _e( 'Include:' ); ?></label>
		<input id="<?php echo $this->get_field_id('include'); ?>" name="<?php echo $this->get_field_name('include'); ?>" type="text" value="<?php echo $include; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by' ); ?></label>
		<select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
			<?php
				$arr=array(
					'id','name','slug','count','term_group'
				);
				foreach ($arr as $str) {
					$selected=($str==$orderby) ? ' selected' : '';
					?><option value="<?=$str?>"<?=$selected?>><?=$str?></option><?php
				}
			?>
		</select>
		<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
			<?php
				$arr=array('ASC'=>__('ASC',true),'DESC'=>__('DESC',true));
				foreach ($arr as $k=>$str) {
					$selected=($k==$order) ? ' selected' : '';
					?><option value="<?=$k?>"<?=$selected?>><?=$str?></option><?php
				}
			?>
		</select>

		</p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Show as dropdown' ); ?></label><br />

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_option_all'); ?>" name="<?php echo $this->get_field_name('show_option_all'); ?>"<?php checked( $show_option_all ); ?> />
		<label for="<?php echo $this->get_field_id('show_option_all'); ?>"><?php _e( 'Show Option All' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>"<?php checked( $hide_empty ); ?> />
		<label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide empty' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>"<?php checked( $show_count ); ?> />
		<label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('use_desc_for_title'); ?>" name="<?php echo $this->get_field_name('use_desc_for_title'); ?>"<?php checked( $use_desc_for_title ); ?> />
		<label for="<?php echo $this->get_field_id('use_desc_for_title'); ?>"><?php _e( 'Use Desc for Title' ); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e( 'Feed' ); ?></label>
		<input id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" type="text" value="<?php echo $feed; ?>" /><br />
		<label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e( 'Feed image' ); ?></label>
		<input id="<?php echo $this->get_field_id('feed_image'); ?>" name="<?php echo $this->get_field_name('feed_image'); ?>" type="text" value="<?php echo $feed_image; ?>" /></p>
<center>Check <a href="http://codex.wordpress.org/Template_Tags/wp_list_categories" target="_blank">wp_list_categories</a> for help with these parameters.</center>

<?php
	}

}
function wp_alpha_categories_init() {
	register_widget('WP_Widget_Alpha_Categories');
}
add_action('widgets_init', 'wp_alpha_categories_init');


function wp_alpha_list_categories( $args = '' ) {
	$defaults = array(
		'show_option_all' => '', 'orderby' => 'name',
		'order' => 'ASC', 'show_last_update' => 0,
		'style' => 'list', 'show_count' => 0,
		'hide_empty' => 1, 'use_desc_for_title' => 1,
		'child_of' => 0, 'feed' => '', 'feed_type' => '',
		'feed_image' => '', 'exclude' => '', 'exclude_tree' => '', 'current_category' => 0,
		'hierarchical' => true, 'title_li' => __( 'Categories' ),
		'echo' => 1, 'depth' => 0
	);

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	if ( isset( $r['show_date'] ) ) {
		$r['include_last_update_time'] = $r['show_date'];
	}

	if ( true == $r['hierarchical'] ) {
		$r['exclude_tree'] = $r['exclude'];
		$r['exclude'] = '';
	}

	extract( $r );

	$categories = get_alpha_categories( $r );

	$output = '';
	if ( $title_li && 'list' == $style )
			$output = '<li class="categories">' . $r['title_li'] . '<ul>';

	if ( empty( $categories ) ) {
		if ( 'list' == $style )
			$output .= '<li>' . __( "No categories" ) . '</li>';
		else
			$output .= __( "No categories" );
	} else {
		global $wp_query;

		if( !empty( $show_option_all ) )
			if ( 'list' == $style )
				$output .= '<li><a href="' .  get_bloginfo( 'url' )  . '">' . $show_option_all . '</a></li>';
			else
				$output .= '<a href="' .  get_bloginfo( 'url' )  . '">' . $show_option_all . '</a>';

		if ( empty( $r['current_category'] ) && is_category() )
			$r['current_category'] = $wp_query->get_queried_object_id();

		if ( $hierarchical )
			$depth = $r['depth'];
		else
			$depth = -1; // Flat.

		$output .= walk_category_tree( $categories, $depth, $r );
	}

	if ( $title_li && 'list' == $style )
		$output .= '</ul></li>';

	$output = apply_filters( 'wp_list_categories', $output );

	if ( $echo )
		echo $output;
	else
		return $output;
}
function get_alpha_categories( $args = '' ) {
	$defaults = array('orderby' => 'name', 'order' => 'ASC',
	'hide_empty' => true, 'exclude' => '', 'exclude_tree' => '', 'include' => '',
	'child_of' => 0, 'get' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$categories=get_categories('pad_counts=1&hide_empty=0');
	extract($args);

	if($include){
		$GLOBALS['alpha_include']=explode(',', $include);
		$categories=array_filter($categories,'alpha_include_category');
	}else{
		if($child_of){
			$GLOBALS['alpha_child_of']=$child_of;
			$categories=array_filter($categories,'alpha_child_of_category');
		}
		if($exclude){
			$GLOBALS['alpha_exclude']=explode(',', $exclude);
			$categories=array_filter($categories,'alpha_exclude_category');
		}
		if($hide_empty){
			$categories=array_filter($categories,'alpha_hide_empty_category');
		}
	}
		
	if($orderby!='name'){
		if($orderby=='count')
			$orderby='category_count';
		elseif($orderby=='id')
			$orderby='term_id';
		
		$categories=alpha_flat_sort_categories($categories,$orderby);
	}
	if(strtolower($order) !='asc'){
		$categories=array_reverse($categories);
	}

	foreach ( array_keys( $categories ) as $k )
		_make_cat_compat( $categories[$k] );

	return $categories;
}
function alpha_include_category($category) {
	return in_array($category->term_id, $GLOBALS['alpha_include']);
}
function alpha_child_of_category($category) {
	return $category->category_parent==$GLOBALS['alpha_child_of'];
}
function alpha_exclude_category($category) {
	return !in_array($category->term_id, $GLOBALS['alpha_exclude']);
}
function alpha_hide_empty_category($category) {
	return $category->category_count>0;
}
function alpha_flat_sort_categories($categories,$orderby) {
	foreach($categories as $cat){
		$key=$cat->$orderby;

		if($orderby=='category_count'){
			$key.='-'.$cat->term_id;
		}
		$new[$key]=$cat;
	}
	ksort($new);
	return array_values($new);
}
?>
