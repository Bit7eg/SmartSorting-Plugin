<?php
    function show_smart_sorting_options() {
        echo '<div class="wrap">
	    <h1>' . get_admin_page_title() . '</h1>
	    <form method="post" action="options.php">';

        settings_fields('smart-sorting_settings');
        do_settings_sections('smart-sorting_settings');
        submit_button();

        echo '</form></div>';
    }

?>