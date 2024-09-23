<?php
/**
 * Template for the addons page.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu
 */

?>

<div class="wrap eafl-addons">
	<h1><?php echo esc_html_e( 'Upgrade Easy Affiliate Links', 'easy-affiliate-links' ); ?></h1>
	<div class="eafl-addons-bundle-container">
		<h2>Premium Bundle</h2>
		<?php if ( EAFL_Addons::is_active( 'premium' ) ) : ?>
		<p>You already have these features!</p>
		<?php else : ?>
		<ul>
			<li>Automatically check for broken links</li>
			<li>See the full data for every click</li>
			<li>Analyze clicks over time for any time period</li>
			<li>Compare specific links or link categories for any time period</li>
			<li>Find out which links are performing the best</li>
			<li>Plot the details for any affiliate link</li>
			<li>...and more coming up!</li>
		</ul>
		<div class="eafl-addons-button-container">
			<a class="button button-primary" href="https://bootstrapped.ventures/easy-affiliate-links/get-the-plugin/" target="_blank">Learn More</a>
		</div>
		<?php endif; // Premium active. ?>
	</div>

	<div class="eafl-addons-bundle-container">
		<h2>Upcoming Features</h2>
		<p>We have a lot of additional features planned for Easy Affiliate Links.</p>
		<p>Sign up using the form below for more information and to get notified as soon as they are available.</p>
		<?php
		$current_user = wp_get_current_user();
		$email = $current_user->user_email;
		?>
		<form action="https://www.getdrip.com/forms/56279905/submissions" method="post" class="eafl-drip-form" data-drip-embedded-form="56279905" target="_blank">
			<div>
				<label style="font-weight: bold;" for="fields[email]">Email Address</label><br />
				<input type="email" id="fields[email]" name="fields[email]" value="<?php echo esc_attr( $email ); ?>" style="width: 300px; margin: 5px 0 10px;" />
			</div>
			<div>
				<input type="submit" name="submit" value="Keep me in the loop!" class="button button-primary" data-drip-attribute="sign-up-button" />
			</div>
		</form>
	</div>
</div>
