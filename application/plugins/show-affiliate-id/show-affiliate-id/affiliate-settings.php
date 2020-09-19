<div class="wrap">
    <h2>General Settings</h2>

    <?php   

    $message = null;
    $type = null;

    if( !empty($_POST) ){
    	unset($_POST['submit']);
    	if( get_option( 'affiliate_id' ) == '' ){
    		add_option( 'affiliate_id', $_POST );
            $type = 'updated';
            $message = __( 'Settings saved.', 'my-text-domain' );
    	}else{
            update_option( 'affiliate_id', $_POST ); 
            $type = 'updated';
            $message = __( 'Settings saved.', 'my-text-domain' );
        }
        
        add_settings_error(
            'affiliate_id',
            esc_attr( 'settings_updated' ),
            $message,
            $type
        );

        settings_errors();	
    }



    $settings  = get_option( 'affiliate_id' );
    $status    = $settings['affiliate_status'];
    $text    = ($settings['affiliate_text'] == "") ? 'Affiliate ID is {id}' : $settings['affiliate_text'];
    $bgcolor   = ($settings['affiliate_bgcolor'] == "") ? '#E10E49' : $settings['affiliate_bgcolor'];
    $textcolor = ($settings['affiliate_textcolor'] == "") ? '#fff' : $settings['affiliate_textcolor'];
    $position  = ($settings['affiliate_position'] == "") ? 'bottom' : $settings['affiliate_position'];

    
    ?>

    <form method="post" action="">    
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Status</th>
                <td>
                    <?php $checked = $status ? 'checked' : ''; ?>
                    <input type="checkbox" name="affiliate_status" <?php echo $checked; ?>/> Check to make it enable.
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Text</th>
                <td><input type="text" name="affiliate_text" value="<?php echo $text ?>" /></td>
            </tr>
             
            <tr valign="top">
                <th scope="row">Background color</th>
                <td><input type="text" name="affiliate_bgcolor" value="<?php echo $bgcolor ?>" /></td>
            </tr>
            
            <tr valign="top">
                <th scope="row">Text Color</th>
                <td><input type="text" name="affiliate_textcolor" value="<?php echo $textcolor ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Position</th>
                <td>
                    <select name="affiliate_position">
                        <option value="top" <?php selected( $position, 'top' ); ?>>Top</option>
                        <option value="bottom" <?php selected( $position, 'bottom' ); ?>>Bottom</option>
                        <option value="left" <?php selected( $position, 'left' ); ?>>Left</option>
                        <option value="right" <?php selected( $position, 'right' ); ?>>Right</option>
                        <option value="top-right" <?php selected( $position, 'top-right' ); ?>>Top-Right</option>
                        <option value="top-left" <?php selected( $position, 'top-left' ); ?>>Top-Left</option>
                        <option value="bottom-right" <?php selected( $position, 'bottom-right' ); ?>>Bottom-Right</option>
                        <option value="bottom-left" <?php selected( $position, 'bottom-left' ); ?>>Bottom-Left</option>
                    </select>
                </td>                
            </tr>      
        </table>    
        <?php submit_button(); ?>
    </form>
</div>

