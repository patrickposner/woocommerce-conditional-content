=== WooCommerce Conditional Content ===
Contributors: patrickposner
Tags: woocommerce, restrict content, restrict by user role
Requires at least: 4.6
Tested up to: 5.0
Requires PHP: 7
Stable tag: 1.0

== Description ==

= Features =
Easily show conditional content based on the users role or user name.
It register a custom role called 'special_customer'.

= Examples =

*Usage of the shortcode:*

[conditional-content role="customer"]
<p>This is your content only visibile for customers.</p>
[/conditional-content]

*Usage of the template function*
$valid = conditional_content( 'special_customer' );

if ( true === $valid ) {
	// do something if current user has role special_customer
}
