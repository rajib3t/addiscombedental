<?php
/**
* Class Megamenu 
*
* @package Owctheme
* @uses Walker_Nav_Menu
* @since 1.0
* @author One World Coding
* @link http://oneworldcoding.com

*/
class OWCtheme_MegaMenu {

	function __construct() {
		
		// Add Custom Fields for megamenu
		add_filter( 'wp_setup_nav_menu_item',  array( $this, 'owctheme_add_custom_nav_fields' ) );
	
		// Save Custom Fields for megamenu
		add_action( 'wp_update_nav_menu_item', array( $this, 'owctheme_update_custom_nav_fields'), 100, 3 );
	
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'owctheme_edit_walker_menu_nav'), 100, 2 );
	}


	function owctheme_add_custom_nav_fields( $menu_item ) {
		$menu_item->megamenu	= get_post_meta( $menu_item->ID, 'menuItemHasMegamenu', true );
		$menu_item->bg 			= get_post_meta( $menu_item->ID, 'mega-menu-bg', true );
		return $menu_item;
	}
	
	function owctheme_update_custom_nav_fields( $menu_id, $menu_item_db_id, $menu_item_data ) {
		
		// Active megamenu
		$megamenu = isset( $_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id])?$_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id]:'';
		update_post_meta( $menu_item_db_id, 'menuItemHasMegamenu', $megamenu );
		
		// Active megamenu background
		$bg = isset( $_REQUEST['edit-mega-menu-bg'][$menu_item_db_id]) ? $_REQUEST['edit-mega-menu-bg'][$menu_item_db_id] :'';
		update_post_meta( $menu_item_db_id, 'mega-menu-bg', $bg );
	}
	
	function owctheme_edit_walker_menu_nav($walker,$menu_id) {
		return 'OWCtheme_edit_Walker_Nav_Menu';
	}
	
	
}
new OWCtheme_MegaMenu();



class OWCtheme_edit_Walker_Nav_Menu extends Walker_Nav_Menu {
	/**
	 * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {}

	/**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {}

	/**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     * @since 4.4.0 'nav_menu_item_args' filter was added.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    global $_wp_nav_menu_max_depth;
    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
 
    ob_start();
    $item_id = esc_attr( $item->ID );
    $removed_args = array(
        'action',
        'customlink-tab',
        'edit-menu-item',
        'menu-item',
        'page-tab',
        '_wpnonce',
    );
 
    $original_title = '';
    if ( 'taxonomy' == $item->type ) {
        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
        if ( is_wp_error( $original_title ) )
            $original_title = false;
    } elseif ( 'post_type' == $item->type ) {
        $original_object = get_post( $item->object_id );
        $original_title = get_the_title( $original_object->ID );
    } elseif ( 'post_type_archive' == $item->type ) {
        $original_object = get_post_type_object( $item->object );
        if ( $original_object ) {
            $original_title = $original_object->labels->archives;
        }
    }
 
    $classes = array(
        'menu-item menu-item-depth-' . $depth,
        'menu-item-' . esc_attr( $item->object ),
        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
    );
 
    $title = $item->title;
 
    if ( ! empty( $item->_invalid ) ) {
        $classes[] = 'menu-item-invalid';
        /* translators: %s: title of menu item which is invalid */
        $title = sprintf( __( '%s (Invalid)' ), $item->title );
    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
        $classes[] = 'pending';
        /* translators: %s: title of menu item in draft status */
        $title = sprintf( __('%s (Pending)'), $item->title );
    }
 
    $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;
 
    $submenu_text = '';
    if ( 0 == $depth )
        $submenu_text = 'style="display: none;"';
 
    ?>
    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
        <div class="menu-item-bar">
            <div class="menu-item-handle">
                <span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item' ); ?></span></span>
                <span class="item-controls">
                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                    <span class="item-order hide-if-js">
                        <a href="<?php
                            echo wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'action' => 'move-up-menu-item',
                                        'menu-item' => $item_id,
                                    ),
                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                ),
                                'move-menu_item'
                            );
                        ?>" class="item-move-up" aria-label="<?php esc_attr_e( 'Move up' ) ?>">&#8593;</a>
                        |
                        <a href="<?php
                            echo wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'action' => 'move-down-menu-item',
                                        'menu-item' => $item_id,
                                    ),
                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                ),
                                'move-menu_item'
                            );
                        ?>" class="item-move-down" aria-label="<?php esc_attr_e( 'Move down' ) ?>">&#8595;</a>
                    </span>
                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" href="<?php
                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                    ?>" aria-label="<?php esc_attr_e( 'Edit menu item' ); ?>"><?php _e( '' ); ?></a>
                </span>
            </div>
        </div>
 
        <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
            <?php if ( 'custom' == $item->type ) : ?>
                <p class="field-url description description-wide">
                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                        <?php _e( 'URL' ); ?><br />
                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                    </label>
                </p>
            <?php endif; ?>
            <p class="description description-wide">
                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                    <?php _e( 'Navigation Label' ); ?><br />
                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                </label>
            </p>
            <p class="field-title-attribute field-attr-title description description-wide">
                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                    <?php _e( 'Title Attribute' ); ?><br />
                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                </label>
            </p>
            <p class="field-link-target description">
                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
                    <?php _e( 'Open link in a new tab' ); ?>
                </label>
            </p>
            <p class="field-css-classes description description-thin">
                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                    <?php _e( 'CSS Classes (optional)' ); ?><br />
                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                </label>
            </p>
            <p class="field-xfn description description-thin">
                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
                    <?php _e( 'Link Relationship (XFN)' ); ?><br />
                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                </label>
            </p>
            <p class="field-description description description-wide">
                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                    <?php _e( 'Description' ); ?><br />
                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
                </label>
            </p>
 			<?php if ( $depth == 0 ){  // Show megamenu option ?>
					<p class="field-megamenu description">
						<?php 
	                        $value = get_post_meta( $item_id, 'menuItemHasMegamenu', true);
	                    ?>
	                    <label for="edit-menu-item-megamenu-<?php echo $item_id; ?>">
	                        <?php _e( 'Mega Menu','owctheme' ); ?><br/>
	                        <input type="checkbox" value="yes" id="edit-menu-item-megamenu-<?php echo $item_id; ?>" name="edit-menu-item-megamenu[<?php echo $item_id; ?>]" <?php if( $value != "" ) echo "checked='checked'"; ?> />
	                        <?php _e( 'Activate Mega Menu','owctheme' ); ?>
	                    </label>
					</p>
					
					<p class="field-megamenu-bg description description-wide">
						<label for="edit-mega-menu-bg-<?php echo $item_id; ?>">
							<?php _e( 'Background Image URL','owctheme' ); ?><br />
							<input type="text" id="edit-mega-menu-bg-<?php echo $item_id; ?>" class="widefat edit-mega-menu-bg" name="edit-mega-menu-bg[<?php echo $item_id; ?>]" value="<?php echo get_post_meta( $item_id, 'mega-menu-bg', true); ?>" />
							<span class="description"><?php _e('Megamenu Background image','owctheme'); ?></span>
						</label>
					</p>
				<?php } ?>
            <p class="field-move hide-if-no-js description description-wide">
                <label>
                    <span><?php _e( 'Move' ); ?></span>
                    <a href="#" class="menus-move menus-move-up" data-dir="up"><?php _e( 'Up one' ); ?></a>
                    <a href="#" class="menus-move menus-move-down" data-dir="down"><?php _e( 'Down one' ); ?></a>
                    <a href="#" class="menus-move menus-move-left" data-dir="left"></a>
                    <a href="#" class="menus-move menus-move-right" data-dir="right"></a>
                    <a href="#" class="menus-move menus-move-top" data-dir="top"><?php _e( 'To the top' ); ?></a>
                </label>
            </p>
 
            <div class="menu-item-actions description-wide submitbox">
                <?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
                    <p class="link-to-original">
                        <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                    </p>
                <?php endif; ?>
                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                echo wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'delete-menu-item',
                            'menu-item' => $item_id,
                        ),
                        admin_url( 'nav-menus.php' )
                    ),
                    'delete-menu_item_' . $item_id
                ); ?>"><?php _e( 'Remove' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
            </div>
 
            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
        </div>
        <ul class="menu-item-transport"></ul>
    <?php
    $output .= ob_get_clean();
}
}


class OWCtheme_Walker_Nav_Menu extends Walker_Nav_Menu {

	var $active_megamenu	= 0;
	var $active_megamenu_bg	= '';
	/**
	* Starts the list before the elements are added.
	*
	* @see Walker::start_lvl()
	*
	* @since 3.0.0
	*/
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu {tag_ul_class}\"{tag_ul_style}>\n";
	}
	/**
	* Ends the list of after the elements are added.
	*
	* @see Walker::end_lvl()
	*
	* @since 3.0.0
	*/
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
		

		if( $depth === 0 )
		{
			if( $this->active_megamenu )
			{
				
				if( $this->active_megamenu_bg ){

					$style	= ' style="background-image:url('. $this->active_megamenu_bg .');"';
					$output	= str_replace("{tag_ul_class}", " ", $output);
					$output	= str_replace("{tag_ul_style}", $style, $output);	
					
				} else {

					$output = str_replace("{tag_ul_class}", " ", $output);
					$output = str_replace("{tag_ul_style}", "", $output);
					
				}
			} 
			else
			{
				$output = str_replace("{tag_ul_class}", "", $output);
				$output = str_replace("{tag_ul_style}", "", $output);
			}
		}
	}
	/**
	 * 
	* Start the element output.
	*
	* @see Walker::start_el()
	*
	* @since 3.0.0
	* @since 4.4.0 'nav_menu_item_args' filter was added.
	*/    
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {   
		global $wp_query;

		$item_output = $li_text_block_class = $megamenuCls =  "";
		if( $depth === 0 ){  
			$this->active_megamenu	= get_post_meta( $item->ID, 'menuItemHasMegamenu', true);
			$this->active_megamenu_bg	= get_post_meta( $item->ID, 'mega-menu-bg', true);
		}
           
		if( $depth === 0 && $this->active_megamenu ){
     		
			$megamenuCls = ' megaSubmenu ';
			

				$title = apply_filters( 'the_title', $item->title, $item->ID );
				
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
                $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
                $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
                $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';       
                
				$item_output .= $args->before;
				$item_output .= '<a class="nav-link"'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= '</a>';
				$item_output .= $args->after;
                
		} else {
			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			
			if($depth === 0){
			   $aclass = 'class="nav-link"';
			}else{
			    $aclass ='';
			}
			$item_output .= $args->before;
				$item_output .= '<a  '.$aclass.' '. $attributes .'>';
					$description = trim($item->description) ? '<span class="description">'. $item->description .'</span>' : false;
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $description . $args->link_after;
				$item_output .= '</a>';
			$item_output .= $args->after;
		}
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		if(in_array('menu-item-has-children',$item->classes )){
		    $arow = 'with-caret ';
		}else{
		    $arow = '';
        }
        $active = '';
        if(in_array('current-menu-item',$item->classes)){
            $active = 'active';
        }
		$class_names = ' class=" nav-item '.$active.' '. $arow .$li_text_block_class . esc_attr( $class_names ) . $megamenuCls. '"';
		if( $depth === 0 ){  
		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}else{
		    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value .'>';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
	
}

// Allow HTML descriptions in WordPress Menu
remove_filter('nav_menu_description', 'strip_tags');
add_filter( 'wp_setup_nav_menu_item', 'owctheme_wp_setup_nav_menu_item' );
function owctheme_wp_setup_nav_menu_item($menu_item) {
               if ( isset( $menuItem->post_type ) && 'nav_menu_item' == $menuItem->post_type ) {
				    $menu_item->description = apply_filters( 'nav_menu_description',  $menu_item->post_content );
			   }
                return $menu_item;
}




class OWCtheme_Walker_Footer_Menu extends Walker_Nav_Menu {

	var $active_megamenu	= 0;
	var $active_megamenu_bg	= '';
	/**
	* Starts the list before the elements are added.
	*
	* @see Walker::start_lvl()
	*
	* @since 3.0.0
	*/
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul>\n";
	}
	/**
	* Ends the list of after the elements are added.
	*
	* @see Walker::end_lvl()
	*
	* @since 3.0.0
	*/
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
		

		
	}
	/**
	 * 
	* Start the element output.
	*
	* @see Walker::start_el()
	*
	* @since 3.0.0
	* @since 4.4.0 'nav_menu_item_args' filter was added.
	*/    
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {   
		global $wp_query;

		 
			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			
			$item_output .= $args->before;
				$item_output .= '<a  '.$aclass.' '. $attributes .'><i class="fa fa-angle-right" aria-hidden="true"></i>';
					$description = trim($item->description) ? '<span class="description">'. $item->description .'</span>' : false;
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $description . $args->link_after;
				$item_output .= '</a>';
			$item_output .= $args->after;
		
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		
        
		$class_names = ' class="fa fa-angle-right"';
		
		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value .'>';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		
	}
	
}

