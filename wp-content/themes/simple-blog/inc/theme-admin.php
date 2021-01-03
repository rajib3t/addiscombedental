<?php

add_filter('admin_init', 'my_general_settings_register_fields');
 
function my_general_settings_register_fields()
{
    register_setting('general', 'contact_email', 'esc_attr');
    register_setting('general', 'contact_address', 'esc_attr');
    register_setting('general', 'contact_phone', 'esc_attr');
    add_settings_field('contact_email', '<label for="contact_email">'.__('Contact Email' , 'simple-blog' ).'</label>' , 'my_general_settings_fields_html1', 'general');
    add_settings_field('contact_address', '<label for="contact_address">'.__('Contact Address' , 'simple-blog' ).'</label>' , 'my_general_settings_fields_html2', 'general');
    add_settings_field('contact_phone', '<label for="contact_phone">'.__('Contact Phone' , 'simple-blog' ).'</label>' , 'my_general_settings_fields_html3', 'general');

}
 
function my_general_settings_fields_html1()
{
    $contact_email = get_option( 'contact_email', '' );
    echo '<input type="text" id="contact_email" name="contact_email" value="' . $contact_email . '" />';
    
}
function my_general_settings_fields_html2()
{
    
    $contact_address = get_option( 'contact_address', '' );
    echo '<textarea name="contact_address">'.$contact_address.'</textarea>';
    
}
function my_general_settings_fields_html3()
{
    
    $contact_phone = get_option( 'contact_phone', '' );
    echo '<input type="text" id="contact_phone" name="contact_phone" value="' . $contact_phone . '" />';
}