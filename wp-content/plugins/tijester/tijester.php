<?php

/*
Plugin Name: TiJester
Plugin URI: 
Description: TiJester plugins
Version: 0.0.1
Author: TiJester
Author URI: https://github.com/TiJester/tijester
License: GPLv2
*/

/* 
Copyright (C) 2016 TiJester (email: tijester@ukr.net)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

// функция отвечает за вывод страницы настроек

/*    function add_my_setting(){ /*лист настроек*/
 /*   echo "<h1><center>".get_admin_page_title()."</center></h1>"; //заголовок на странице
    echo "<hr>"; 
}
*/
    add_action('admin_menu', 'add_plugin_page');
    function add_plugin_page(){ //подключам меню
        add_menu_page( 'Настройки', 'TiJester Настройки', 'manage_options', 'site-options', 'add_my_setting', 'dashicons-format-status', 80.1 ); 

} 

function add_my_setting(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'option_group' );     // скрытые защитные поля
				do_settings_sections( 'primer_page' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'plugin_settings');
function plugin_settings(){
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'option_group', 'option_name', 'sanitize_callback' );

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'section_id', 'Основные настройки', '', 'primer_page' ); 

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('primer_field1', 'Название опции', 'fill_primer_field1', 'primer_page', 'section_id' );
	add_settings_field('primer_field2', 'Другая опция', 'fill_primer_field2', 'primer_page', 'section_id' );
}

## Заполняем опцию 1
function fill_primer_field1(){
	$val = get_option('option_name');
	$val = $val['input'];
	?>
	<input type="text" name="option_name[input]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

## Заполняем опцию 2
function fill_primer_field2(){
	$val = get_option('option_name');
	$val = $val['checkbox'];
	?>
	<label><input type="checkbox" name="option_name[checkbox]" value="1" <?php checked( 1, $val ) ?> /> отметить</label>
	<?php
}

## Очистка данных
function sanitize_callback( $options ){ 
	// очищаем
	foreach( $options as $name => & $val ){
		if( $name == 'input' )
			$val = strip_tags( $val );

		if( $name == 'checkbox' )
			$val = intval( $val );
	}

	//die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

	return $options;
}
