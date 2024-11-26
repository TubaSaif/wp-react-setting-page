<?php

if ( ! current_user_can( 'manage_options' ) ) {
   wp_die( esc_html__( 'You do not have sufficient capabilities to access this page.', 'react-settings-page' ) );
}

?>

<div class="wrap">

    <div id="my-react-app"></div>

</div>