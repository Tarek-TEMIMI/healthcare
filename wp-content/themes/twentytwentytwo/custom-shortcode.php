<?php

// Add Shortcode
function subform() {

    // Get the current user's display name
    $user_display_name = wp_get_current_user()->user_firstname;

    // Get the blog name
    $blog_name = get_bloginfo( 'name' );

    // Display the welcome message and subscription form
    echo '<p>Hey ' . $user_display_name . ', welcome to ' . $blog_name . '! You can subscribe to our newsletter here:</p>';
    ?>
    <form action="/thank-you">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email">
        <input type="submit" value="Submit">
    </form>
    <?php
}
add_shortcode( 'subscriptionform', 'subform' );
 
function hello_world() {
	return '<h1>Hello World!!!!</h1>';
  }
  
  function register_shortcodes(){
	 add_shortcode('helloworld', 'hello_world');
  }
  
  add_action( 'init', 'register_shortcodes');

  function newsletter_subscription_form() {
    // Output the form HTML
    ?>
    <form action="#" method="post">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Subscribe">
    </form>
    <?php
}

// Register the shortcode
add_shortcode('newsletter_subscription', 'newsletter_subscription_form');
 ?>