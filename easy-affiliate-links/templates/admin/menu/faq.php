<?php
/**
 * Template for the Easy Affiliate Links FAQ.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu
 */
// Active version.
$name = 'Easy Affiliate Links';
$version = EAFL_VERSION;
$full_name = $name . ' ' . $version;

// Image directory.
$img_dir = EAFL_URL . 'assets/images/faq';
?>

<div class="wrap about-wrap eafl-faq">
	<h1><?php echo esc_html( $name ); ?></h1>
	<div class="about-text">Welcome to version <?php echo esc_html( $version ) ?>! Check out the <a href="https://help.bootstrapped.ventures/article/141-easy-affiliate-links-changelog" target="_blank">changelog</a> now.</div>
	<div class="eafl-badge">Version <?php echo esc_html( $version ); ?></div>

	<h3>Getting Started with EAFL</h3>
	<p>
		Not sure how to get started with Easy Affiliate Links? Check out the <a href="https://help.bootstrapped.ventures/category/136-getting-started" target="_blank">Getting Started section of our documentation</a>!
	</p>

	<h3>I need more help</h3>
	<p>
		Check out <a href="https://help.bootstrapped.ventures/collection/133-easy-affiliate-links" target="_blank">all documentation for Easy Affiliate Links</a> or contact us using the blue question mark in the bottom right of this page or by emailing <a href="mailto:support@bootstrapped.ventures">support@bootstrapped.ventures</a> directly.
	</p>

	<h3>We would love to learn from you!</h3>
	<p>
		We want Easy Affiliate Links to become the best affiliate link management plugin it can be and to do so we need your help.
	</p>
	<p>
		Leave your email address here and you'll receive sporadic surveys (less than 1 per month) that help us improve the plugin.
	</p>
	<p>
		Your input will help shape the plugin!
	</p>
	<?php
	$current_user = wp_get_current_user();
	$email = $current_user->user_email;
	$website = get_site_url();
	?>
	<form action="https://www.getdrip.com/forms/86110294/submissions" method="post" class="eafl-drip-form" data-drip-embedded-form="86110294" target="_blank">
			<div>
					<label for="fields[email]">Email Address</label><br />
					<input type="email" id="fields[email]" name="fields[email]" value="<?php echo esc_attr( $email ); ?>" />
			</div>
			<div>
					<label for="fields[website]">Website</label><br />
					<input type="text" id="fields[website]" name="fields[website]" value="<?php echo esc_attr( $website ); ?>" />
			</div>
		<div>
			<input type="submit" name="submit" value="I want you to help improve the plugin!" class="button button-primary" data-drip-attribute="sign-up-button" />
		</div>
	</form>
</div>