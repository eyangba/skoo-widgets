<?php
// register Skoo_Widget widget
function register_skoo_widget() {
    register_widget( 'Skoo_Post_List_Widget' );
}
add_action( 'widgets_init', 'register_skoo_widget' );

/**
 * Skoo_Post_List_Widget widget.
 */
class Skoo_Post_List_Widget extends Skoo_Widget {


	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'skoo_post_list_widget', // Base ID
			'文章列表', // Name
			array( 'description' => __( '文章标题列表', 'skoo' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$cat = $instance['cat'];
		$limit = $instance['limit'];
		$ellipsis = $instance['ellipsis'];
		$time = $instance['time'];
		$position = $instance['position'];
		
		$ellipsis_class = $ellipsis ? ' ellipsis' : '';		

		$args = array( 'numberposts' => $limit, 'category' => $cat );

		$recent_posts = wp_get_recent_posts( $args );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
			echo '<ul class="skoo_widget_content">';
		        foreach( $recent_posts as $recent ){
					if ($time) {
						$time_str = '<div class="date ' . $position . '">' . mysql2date("m-d", $recent["post_date"]) . '</div>';
					}
                	echo '<li>' . $time_str . '<div class="title' . $ellipsis_class . '"><a href="' . get_permalink($recent["ID"]) . '" title="'.esc_attr($recent["post_title"]).'" >' . $recent["post_title"] . '</a></div></li> ';
        		}
        		echo '</ul>';

		echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// Pull all the categories into an array
		$options_categories = array();
		$options_categories_obj = get_categories(array( 'orderby' => 'id', 'order' => 'ASC', 'hide_empty' => false ));
		foreach ($options_categories_obj as $category) {
			$options_categories[$category->cat_ID] = $category->cat_name;
		}
		
		$time_position = array('left' => '左边', 'right' => '右边');
	
		//Definate options
		$options = array();

		$options[] = array(
			'name' => '标题',
			'id' => 'title',
			'std' => '请输入标题',
			'type' => 'text');
	
		$options[] = array(
			'name' => '分类',
			'id' => 'cat',
			'type' => 'select',
			'options' => $options_categories);
		
		$options[] = array(
			'name' => '数量',
			'id' => 'limit',
			'std' => '5',
			'type' => 'text');

		$options[] = array(
			'name' => '',
			'desc' => '单行显示，超出部分自动截断。',
			'id' => 'ellipsis',
			'std' => 1,
			'type' => 'checkbox');

		$options[] = array(
			'name' => '',
			'desc' => '显示时间',
			'id' => 'time',
			'std' => 0,
			'type' => 'checkbox');
			
		$options[] = array(
			'name' => '显示位置',
			'id' => 'position',
			'std' => 'right',
			'hidden_by' => 'time',
			'hidden_condition' => 0,
			'type' => 'radio',
			'options' => $time_position);
			
			
		// Build options
		$this->build_options($options, $instance);
		
		$time_id = $this->get_field_id( 'time' );
		$position_wrap = $this->get_field_id( 'position' ) . '_wrap';
		
		?>

		<script type='text/javascript'>
		/* <![CDATA[ */
		if (typeof(timeCheckFunc) == "function") {
			var timeCheck = document.getElementById("<?php echo $time_id; ?>");
			var position = document.getElementById("<?php echo $position_wrap; ?>");
			function timeCheckFunc() {
				if ( timeCheck.checked ) {
					var newClass = ' ' + position.className.replace( /[\t\r\n]/g, ' ') + ' ';
					while (newClass.indexOf(' ' + 'hidden' + ' ') >= 0 ) {
						newClass = newClass.replace(' ' + 'hidden' + ' ', ' ');
					}
					position.className = newClass.replace(/^\s+|\s+$/g, '');
				} else {
					position.className += " hidden";
				}
			}
		}
		timeCheck.onclick = timeCheckFunc;
		/* ]]> */
		</script>
		
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cat'] = $new_instance['cat'];
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['ellipsis'] = ( ! empty( $new_instance['ellipsis'] ) ) ? 1 : 0;
		$instance['time'] = ( ! empty( $new_instance['time'] ) ) ? 1 : 0;
		$instance['position'] = $new_instance['position'];

		return $instance;
	}

} // class Skoo_Post_List_Widget




?>