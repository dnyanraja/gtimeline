<h1>GTimeline Plugin Options</h1>
<?php settings_errors(); ?>
<?php 
/*	$picture = esc_attr(get_option('profile_picture'));
	$firstName = esc_attr(get_option('first_name'));
	$lastName = esc_attr(get_option('last_name'));
	$fullName= $firstName.' '.$lastName;
	$userDesc = esc_attr(get_option('user_description'));*/
?>

<form method="post" action="options.php" class="ganesh-general-form">

<?php settings_fields('gtimeline-settings-group'); ?>
<?php do_settings_sections('gtimeline_slug'); ?>
<?php submit_button('Save Changes', 'primary', 'btnSubmit'); ?>
<p class="description"><strong>Note:</strong> use [gtimline] shortcode to display timeline anywhere in your site.</p>
</form>