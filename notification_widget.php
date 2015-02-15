<?php
/**
 * Plugin Name: Notification Widget
 * Description: A fully customizable widget for your website.
 * Version: 1.0.0
 * Author: Perpetual Motion Developers UG(haftungsbeschränkt)
 * Author URI: http://pm-dev.de
 * License: GPLv2
 */
 defined('ABSPATH') or die("don't even try");
 
 global $jal_db_version;
$jal_db_version = '1.0';

function install() 
{
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'noti_widget';
	
	$charset_collate = '';

	if ( ! empty( $wpdb->charset ) ) {
	  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	}

	if ( ! empty( $wpdb->collate ) ) {
	  $charset_collate .= " COLLATE {$wpdb->collate}";
	}

	$sql = "CREATE TABLE $table_name (
		barcolor text NOT NULL,
		bgcolor text NOT NULL,
		bartext text NOT NULL,
		maintext text NOT NULL,
		closedistance text NOT NULL,
		closeheight text NOT NULL,
		closewidth text NOT NULL,
		barheight text NOT NULL,
		boxwidth text NOT NULL,
		boxheight text NOT NULL,
		boxposx text NOT NULL,
		boxposy text NOT NULL
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );
	

	$regText = "why not <a href='register.php' >register now</a>?";

	$wpdb->insert($table_name, array(
	"barcolor" => "#000000",
	"bgcolor" => "#000099",
	"bartext" => "" ,
	"maintext" => $regText,
 	"closedistance" => "4px",
	"closeheight" => "30px",
	"closewidth" => "30px" ,
	"barheight" => "40px",
	"boxwidth" => "300px",
	"boxheight" => "150px",
	"boxposx" => "1px" ,
	"boxposy" => "1px",
	));
	
	add_option( 'jal_db_version', $jal_db_version );
}

function addWidget()
{
	if(isset($_COOKIE['widgetclosed']))
	{
		return;
	}
	if( is_user_logged_in() )
	{
		return;
	}

	global $wpdb;
	
	$table_name = $wpdb->prefix . 'noti_widget';
	$rows = $wpdb->get_results( 
		"
		SELECT * 
		FROM $table_name
		WHERE 1
		"
	);



	foreach ( $rows as $row ) 
	{
		$barcolor = $row->barcolor;
		$bgcolor = $row->bgcolor;
		$bartext = $row->bartext;
		$maintext = htmlspecialchars_decode($row->maintext);
		$closedistance = $row->closedistance;
		$closeheight = $row->closeheight;
		$closewidth = $row->closewidth;
		$barheight = $row->barheight;
		$boxwidth = $row->boxwidth;
		$boxheight = $row->boxheight;
		$boxposx = $row->boxposx;
		$boxposy = $row->boxposy;
		break;
	}
	?>
	
	
	
		<div id="dragme" style="position: fixed;z-index: 99;bottom: <?php echo $boxposx; ?>; /* POSITION TOP BOTTOM */ right: <?php echo $boxposy; ?>; /* POSITION LEFT RIGHT */ height: <?php echo $boxheight; ?>; /* HEIGHT BAR */ width: <?php echo $boxwidth; ?>; /* WIDTH BAR */ background: <?php echo $bgcolor; ?>; /* COLOR BOX */ -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.05), 0 -1px 0 rgba(0,0,0,0.2), inset 1px 0 0 rgba(0,0,0,0.2), inset -1px 0 0 rgba(0,0,0,0.2);box-shadow: inset 0 1px 0 rgba(255,255,255,0.05), 0 -1px 0 rgba(0,0,0,0.2), inset 1px 0 0 rgba(0,0,0,0.2), inset -1px 0 0 rgba(0,0,0,0.2);color: rgba(255,255,255,0.7);"> 
            <div class="oberleiste" style="height: <?php echo $barheight; ?>; /* HEIGHT BAR */ width: <?php echo $boxwidth; ?>; /* WIDTH BAR */ background-color: <?php echo $barcolor; ?>; /* COLOR BAR */">
                <?php echo $bartext; ?>
				<a class="closebutton" onclick="javascript:document.getElementById('dragme').style.display='none';var date = new Date();date.setTime(date.getTime() + 1800 * 1000);var expires = 'expires='+date.toUTCString();document.cookie = 'widgetclosed=true; ' + expires;" style="background-color: #eaeaea;border: 1px #888888 solid;height: <?php echo $closeheight; ?>; /* HEIGHT BUTTON */ width: <?php echo $closewidth; ?>; /* WIDTH BUTTON */ float: right;margin: <?php echo $closedistance; ?>; /* DISTANCE */ -webkit-border-radius: 0 0 3px 3px;border-radius: 3px 3px 3px 3px;text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="pointer-events: none; display: block;"><g id="close"><polygon points="19,6.4 17.6,5 12,10.6 6.4,5 5,6.4 10.6,12 5,17.6 6.4,19 12,13.4 17.6,19 19,17.6 13.4,12 "></polygon></g></svg>
                </a>      
            </div>
            <?php echo $maintext;?>		
		</div>
	<?php
}


 
function setup_admin_menu()
{
    add_menu_page( 'Notification Widget', 'Notification Widget', 'manage_options', 'noti_widget', 'admin_init' );
}
 
function admin_init()
{
	global $wpdb;

	getData();

	$table_name = $wpdb->prefix . 'noti_widget';
	$rows = $wpdb->get_results( 
		"
		SELECT * 
		FROM $table_name
		WHERE 1
		"
	);



	foreach ( $rows as $row ) 
	{
		$barcolor = $row->barcolor;
		$bgcolor = $row->bgcolor;
		$bartext = $row->bartext;
		$maintext = $row->maintext;
		$closedistance = $row->closedistance;
		$closeheight = $row->closeheight;
		$closewidth = $row->closewidth;
		$barheight = $row->barheight;
		$boxwidth = $row->boxwidth;
		$boxheight = $row->boxheight;
		$boxposx = $row->boxposx;
		$boxposy = $row->boxposy;
		break;
	}


	echo "<h1>Settings</h1>";
	echo "<h2>Style</h2>";
	?>
		<form action="" method="post">
		Enter Barcolor (Colorcode):<br/>
		<input value="<?php echo $barcolor ?>" type="text" name="barcolor"><br/>

		Enter Bgcolor (Colorcode):<br/>
		<input value="<?php echo $bgcolor ?>" type="text" name="bgcolor"><br/>

		Enter Bartext (Text):<br/>
		<input value="<?php echo $bartext?>" type="text" name="bartext"><br/>

		Enter Maintext (Text):<br/>
		<textarea type="textarea" rows="5" name="maintext"><?php echo $maintext ?></textarea>
		<br/>


	<?php

	echo "<h3>Layout</h3>";

	?>
		Enter distdance to margin of the closebutton (px):<br/>
		<input value="<?php echo $closedistance ?>" type="text" name="closedistance"><br/>

		Enter height of the closebutton (px):<br/>
		<input value="<?php echo $closeheight ?>" type="text" name="closeheight"><br/>

		Enter width of the closebutton (px):<br/>
		<input value="<?php echo $closewidth ?>" type="text" name="closewidth"><br/>

		Enter height of the bar (px):<br/>
		<input value="<?php echo $barheight?>" type="text" name="barheight"><br/>

		Enter width of the box (px):<br/>
		<input value="<?php echo $boxwidth ?>" type="text" name="boxwidth"><br/>

		Enter height of the box (px):<br/>
		<input value="<?php echo $boxheight ?>" type="text" name="boxheight"><br/>

		Enter X-position of the box from the margin (px):<br/>
		<input value="<?php echo $boxposx?>" type="text" name="boxposx"><br/>

		Enter Y-position of the box from the margin (px):<br/>
		<input value="<?php echo $boxposy?>" type="text" name="boxposy"><br/>
		<br/>
		<input type="submit" name="submit" value="Enter"><br/>

		</form>
	<?php




}



function getData()
{

	if(!isset($_POST["submit"]))
	{
		return;
	}

	$barcolor = sanitize_text_field($_POST["barcolor"]);
	$bgcolor = sanitize_text_field($_POST["bgcolor"]);
	$bartext = sanitize_text_field($_POST["bartext"]);
	$maintext = sanitize_text_field(esc_html($_POST["maintext"]));
	$closedistance = sanitize_text_field($_POST["closedistance"]);
	$closeheight = sanitize_text_field($_POST["closeheight"]);
	$closewidth = sanitize_text_field($_POST["closewidth"]);
	$barheight = sanitize_text_field($_POST["barheight"]);
	$boxwidth = sanitize_text_field($_POST["boxwidth"]);
	$boxheight = sanitize_text_field($_POST["boxheight"]);
	$boxposx = sanitize_text_field($_POST["boxposx"]);
	$boxposy = sanitize_text_field($_POST["boxposy"]);




	if($barcolor == "" or $bgcolor == "" or $bartext == ""
	or $maintext == "" or $closedistance == "" or $closeheight == ""
	or $closewidth == "" or $barheight == "" or $boxwidth == ""
	or $boxheight == "" or $boxposx == "" or $boxposy == "")
	{
		echo "<h1>Error occurred:</h1>";
		echo"______________________________________________________________________";?><br/><?php

		if($barcolor == "")
		{
			echo"Error: missing barcolor: set to standart (#000000)";?><br/><?php
			$barcolor = "#000000"; 
		}

		if($bgcolor == "")
		{
			echo"Error: missing bgcolor: set to standart (#000099)";?><br/><?php
			$bgcolor = "#000099";
		}
		if($bartext == "")
		{
			echo"Error: missing bartext: set to standart (NONE)";?><br/><?php
			$bartext = ""; 
		}

		if($maintext == "")
		{
			echo"Error: missing maintext: set to standart (standart text)";?><br/><?php
			$regText = "why not <a href='register.php' >register now</a>?";
			$maintext = $regText;
		}


		if($closedistance == "")
		{
			echo"Error: missing closebutton margin distance: set to standart (4px)";?><br/><?php
			$closedistance = "4px";
		}
		if($closeheight == "")
		{
			echo"Error: missing height of closebutton: set to standart (30px)";?><br/><?php
			$closeheight = "#30px";
		}
		if($closewidth == "")
		{
			echo"Error: missing width of closebutton: set to standart (30px)";?><br/><?php
			$closewidth = "30px";
		}
		if($barheight == "")
		{
			echo"Error: missing height of the bar: set to standart (40px)";?><br/><?php
			$barheight = "40px";
		}
		if($boxwidth == "")
		{
			echo"Error: missing width of the box: set to standart (300px)";?><br/><?php
			$boxwidth = "300px";
		}
		if($boxheight == "")
		{
			echo"Error: missing height of the box: set to standart (150px)";?><br/><?php
			$boxheight = "150px";
		}
		if($boxposx == "")
		{
			echo"Error: missing x-distance to margin: set to standart (1px)";?><br/><?php
			$boxposx = "1px";
		}
		if($boxposy == "")
		{
			echo"Error: missing y-distance to margin: set to standart (1px)";?><br/><?php
			$boxposy = "1px";
		}
		echo"______________________________________________________________________";
	}
	global $wpdb;

	$table_name = $wpdb->prefix . 'noti_widget';

	$wpdb->query($wpdb->prepare("UPDATE $table_name SET barcolor= %s , bartext= '$bartext' , bgcolor= %s , maintext= '$maintext', 
		closedistance= '$closedistance', closeheight= '$closeheight', closewidth= '$closewidth', barheight= '$barheight',
		boxwidth= '$boxwidth', boxheight= '$boxheight', boxposx= '$boxposx', boxposy= '$boxposy' WHERE 1", $barcolor, $bgcolor));

}


register_activation_hook( __FILE__, 'install' );

add_action('admin_menu', 'setup_admin_menu');

add_action('wp_footer', 'addWidget');

?>