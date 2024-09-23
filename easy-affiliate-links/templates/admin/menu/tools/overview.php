<?php
/**
 * Template for tools page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/tools
 */
?>
<div class="wrap eafl-tools">
    <h1><?php esc_html_e( 'Easy Affiliate Links Tools', 'easy-affiliate-links' ); ?></h1>
<?php foreach ( $tools as $group ) : ?>
    <?php if ( isset( $group['header'] ) ) : ?>
    <h2><?php echo $group['header']; ?></h2>
    <?php endif; // Group Header.?>
    <?php if ( isset( $group['description'] ) ) : ?>
    <p><?php echo $group['description']; ?></p>
    <?php endif; // Group Header.?>
    <table class="form-table">
		<tbody>
            <?php foreach ( $group['tools'] as $tool ) : ?>
            <tr>
                <th scope="row">
					<?php if ( isset( $tool['label'] ) ) { echo $tool['label']; } ?>
				</th>
				<td>
                <a href="<?php echo esc_url( isset( $tool['url'] ) ? $tool['url'] : '#' ); ?>" class="button" id="eafl_tools_<?php echo esc_attr( $tool['id'] ); ?>"><?php echo esc_html( $tool['name'] ); ?></a>
                    <?php if ( isset( $tool['description'] ) ) { echo '<p class="description">' . $tool['description']. '</p>'; } ?>
				</td>
            </tr>
            <?php endforeach; // Tool. ?>
        </tbody>
    </table>
<?php endforeach; // Tool group. ?>
</div>
