<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_wpcytxt_display']) && $_POST['frm_wpcytxt_display'] == 'yes')
{
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$wpcytxt_success = '';
	$wpcytxt_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_WPCYTXT_SETTINGS."
		WHERE `wpcytxt_sid` = %d",
		array($did)
	);

	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'wp-cycle-text-announcement'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('wpcytxt_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_WPCYTXT_SETTINGS."`
					WHERE `wpcytxt_sid` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$wpcytxt_success_msg = TRUE;
			$wpcytxt_success = __('Selected record was successfully deleted ('.$did.').', 'wp-cycle-text-announcement');
		}
	}
	
	if ($wpcytxt_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $wpcytxt_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Wp cycle text announcement / Setting management', 'wp-cycle-text-announcement'); ?></h2>
    <!--<h3><?php _e('Setting management', 'wp-cycle-text-announcement'); ?>-->
	<!--<a class="add-new-h2" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=addcycle">Add New</a>--><!--</h3>-->
	<div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_WPCYTXT_SETTINGS."` order by wpcytxt_sid asc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_wpcytxt_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Setting name', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Short code', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Link', 'wp-cycle-text-announcement'); ?></th>
            <th scope="col"><?php _e('Direction', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Speed', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Timeout', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Random', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Action', 'wp-cycle-text-announcement'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Setting name', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Short code', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Link', 'wp-cycle-text-announcement'); ?></th>
            <th scope="col"><?php _e('Direction', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Speed', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Timeout', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Random', 'wp-cycle-text-announcement'); ?></th>
			<th scope="col"><?php _e('Action', 'wp-cycle-text-announcement'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td><?php echo esc_html($data['wpcytxt_sname']); ?></td>
						<td>[cycle-text setting="<?php echo(stripslashes($data['wpcytxt_sname'])); ?>"]</td>
						<td><?php echo esc_html($data['wpcytxt_slink']); ?></td>
						<td><?php echo esc_html($data['wpcytxt_sdirection']); ?></td>
						<td><?php echo esc_html($data['wpcytxt_sspeed']); ?></td>
						<td><?php echo esc_html($data['wpcytxt_stimeout']); ?></td>
						<td><?php echo esc_html($data['wpcytxt_srandom']); ?></td>
						<td>
						<a title="Edit" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=editcycle&amp;did=<?php echo $data['wpcytxt_sid']; ?>"><?php _e('Edit', 'wp-cycle-text-announcement'); ?></a>
						<!--<a onClick="javascript:wpcytxt_content_delete('<?php //echo $data['wpcytxt_sid']; ?>')" href="javascript:void(0);">Delete</a>-->
						</td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="8" align="center"><?php _e('No records available.', 'wp-cycle-text-announcement'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('wpcytxt_form_show'); ?>
		<input type="hidden" name="frm_wpcytxt_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
	  <a href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=show"><input class="button action" type="button" value="<?php _e('Announcement Management', 'wp-cycle-text-announcement'); ?>" /></a>
	  <a href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=showcycle"><input class="button button-primary" type="button" value="<?php _e('Setting Management', 'wp-cycle-text-announcement'); ?>" /></a>
	  <a target="_blank" href="<?php echo Wp_wpcytxt_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'wp-cycle-text-announcement'); ?>" /></a>
	  </div>
	<h3><?php _e('Plugin configuration option', 'wp-cycle-text-announcement'); ?></h3>
	<ol>
		<li><?php _e('Add the plugin in the posts or pages using short code.', 'wp-cycle-text-announcement'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'wp-cycle-text-announcement'); ?></li>
		<li><?php _e('Drag and drop the widget to your sidebar.', 'wp-cycle-text-announcement'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'wp-cycle-text-announcement'); ?>
		<a target="_blank" href="<?php echo Wp_wpcytxt_FAV; ?>"><?php _e('click here', 'wp-cycle-text-announcement'); ?></a>
	</p>
	</div>
</div>