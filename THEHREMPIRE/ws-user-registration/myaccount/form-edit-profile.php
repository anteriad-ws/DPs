<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/user-registration/myaccount/form-edit-profile.php.
 *
 * HOWEVER, on occasion UserRegistration will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wpeverest.com/user-registration/template-structure/
 * @author  WPEverest
 * @package UserRegistration/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'user_registration_before_edit_profile_form' ); ?>

<div class="ur-frontend-form login" id="ur-frontend-form">
	<form class="user-registration-EditProfileForm edit-profile my-account-content-block" action="" method="post" enctype="multipart/form-data">
		<div class="ur-form-row">
			<div class="ur-form-grid">
				<div class="user-registration-profile-fields">
					<h2><?php _e( 'Profile Detail', 'user-registration' ); ?></h2>
					<div class="user-registration-profile-header">
						<div class="user-registration-img-container" style="width:100%">
							<?php
							$gravatar_image      = get_avatar_url( get_current_user_id(), $args = null );
							$profile_picture_url = get_user_meta( get_current_user_id(), 'user_registration_profile_pic_url', true );
							$image               = ( ! empty( $profile_picture_url ) ) ? $profile_picture_url : $gravatar_image;
							?>
							<img class="profile-preview" alt="profile-picture" src="<?php echo $image; ?>" style='max-width:96px; max-height:96px;' >
							<?php
							$max_size = wp_max_upload_size();
							$max_size = size_format( $max_size );
							?>
							<p class="user-registration-tips"><?php echo __( 'Max size: ', 'user-registration' ) . $max_size; ?></p>
						</div>
						<header>
								<p><strong><?php _e( 'Upload your new profile image.', 'user-registration' ); ?></strong></p>
							<div class="button-group">
						<?php

						if ( has_action( 'uraf_profile_picture_buttons' ) ) {
							?>
							<div class="uraf-profile-picture-upload">
								<p class="form-row " id="profile_pic_url_field" data-priority="">
									<span class="uraf-profile-picture-upload-node" style="height: 0;width: 0;margin: 0;padding: 0;float: left;border: 0;overflow: hidden;">
									<input type="file" id="ur-profile-pic" name="profile-pic" class="profile-pic-upload" accept="image/jpeg" style="<?php echo ( $gravatar_image !== $image ) ? 'display:none;' : ''; ?>" />
									<?php echo '<input type="text" class="uraf-profile-picture-input input-text ur-frontend-field" name="profile_pic_url" id="profile_pic_url" value="" />'; ?>
								</span>
								<?php do_action( 'uraf_profile_picture_buttons' ); ?>
							</p>
							<div style="clear:both; margin-bottom: 20px"></div>
						</div>

							<?php
						} else {
							?>
						<input type="hidden" name="profile-pic-url" value="<?php echo $profile_picture_url; ?>" />
						<input type="hidden" name="profile-default-image" value="<?php echo $gravatar_image; ?>" />
						<button class="button profile-pic-remove" style="<?php echo ( $gravatar_image === $image ) ? 'display:none;' : ''; ?>"><?php echo __( 'Remove', 'user-registration' ); ?></php></button>
						<input type="file" id="ur-profile-pic" name="profile-pic" class="profile-pic-upload" accept="image/jpeg" style="<?php echo ( $gravatar_image !== $image ) ? 'display:none;' : ''; ?>" />
							<?php
						}
						?>
						 </div>
						 <?php if ( ! $profile_picture_url ) { ?>
							<span><i><?php echo __( 'You can change your profile picture on', 'user-registration' ); ?> <a href="https://en.gravatar.com/"><?php _e( 'Gravatar', 'user-registration' ); ?></a></i></span>
						<?php } ?>
					</header>
					</div>
					<?php do_action( 'user_registration_edit_profile_form_start' ); ?>
					<div class="user-registration-profile-fields__field-wrapper">

						<?php foreach ( $form_data_array as $data ) { ?>
							<div class='ur-form-row'>
								<?php
								$width = floor( 100 / count( $data ) ) - count( $data );

								foreach ( $data as $grid_key => $grid_data ) {
									$found_field = false;

									foreach ( $grid_data as $grid_data_key => $single_item ) {
										$key = 'user_registration_' . $single_item->general_setting->field_name;
										if ( isset( $single_item->field_key ) && isset( $profile[ $key ] ) ) {
											$found_field = true;
										}
									}
									if ( $found_field ) {
										?>
										<div class="ur-form-grid ur-grid-<?php echo( $grid_key + 1 ); ?>" style="width:<?php echo $width; ?>%;">
										<?php
									}

									foreach ( $grid_data as $grid_data_key => $single_item ) {
										$key = 'user_registration_' . $single_item->general_setting->field_name;
										if ( isset( $profile[ $key ] ) ) {
											$field                = $profile[ $key ];
											$field['input_class'] = array( 'ur-edit-profile-field ' );
											$advance_data         = array(
												'general_setting' => (object) $single_item->general_setting,
												'advance_setting' => (object) $single_item->advance_setting,
											);
											?>
											<div class="ur-field-item field-<?php echo $single_item->field_key; ?>">
												<?php
												$readonly_fields = ur_readonly_profile_details_fields();
												if ( array_key_exists( $field['field_key'], $readonly_fields ) ) {
													$field['custom_attributes'] = array(
														'readonly' => 'readonly',
													);
													if ( isset( $readonly_fields[ $field['field_key'] ] ['value'] ) ) {
														$field['value'] = $readonly_fields[ $field['field_key'] ] ['value'];
													}
													if ( isset( $readonly_fields[ $field['field_key'] ] ['message'] ) ) {
														$field['custom_attributes']['title'] = $readonly_fields[ $field['field_key'] ] ['message'];
														$field['input_class'][]              = 'user-registration-help-tip';
													}
												}

												if ( 'phone' === $single_item->field_key ) {
													$field['phone_format'] = $single_item->general_setting->phone_format;
													if ( 'smart' === $field['phone_format'] ) {
														unset( $field['input_mask'] );
													}
												}

												if ( 'select' === $single_item->field_key ) {
													$field['placeholder'] = $single_item->general_setting->placeholder;
													if ( isset( $field['placeholder'] ) ) {
														unset( $field['placeholder'] );
													}
												}

												if ( 'file' === $single_item->field_key ) {
													if ( isset( $single_item->general_setting->max_files ) ) {
														$field['max_files'] = $single_item->general_setting->max_files;
													} else {
														$field['max_files'] = 1;
													}
												}

												$filter_data = array(
													'form_data' => $field,
													'data' => $advance_data,
												);

												$form_data_array = apply_filters( 'user_registration_' . $field['field_key'] . '_frontend_form_data', $filter_data );
												$field           = isset( $form_data_array['form_data'] ) ? $form_data_array['form_data'] : $field;
												$value           = ! empty( $_POST[ $key ] ) ? ur_clean( $_POST[ $key ] ) : $field['value'];
//                                                                                                echo '<pre>';
//                                                                                                print_r($key);
//                                                                                                echo '</pre>';
//                                                                                                echo '<pre>';
//                                                                                                print_r($value);
//                                                                                                echo '</pre>';

												user_registration_form_field( $key, $field, $value );

//                                                                                                echo '<pre>';
//                                                                                                print_r(UR_Form_Field_Country::get_instance()->get_country());
//                                                                                                echo '</pre>';
												/**
												 * Embed the current country value to allow to remove it if it's not allowed.
												 */
												if ( 'country' === $single_item->field_key && ! empty( $value ) ) {
                                                                                                    $country_arr = UR_Form_Field_Country::get_instance()->get_country();
                                                                                                    $key = array_search($value, $country_arr);
                                                                                                    echo sprintf( '<span hidden class="ur-data-holder" data-option-value="%s" data-option-html="%s"></span>', $value, UR_Form_Field_Country::get_instance()->get_country()[ $key ] );
												}
//                                                                                                if ( 'country' === $single_item->field_key && ! empty( $value ) ) {
//													echo $value;
//												}
												?>
											</div>
										<?php } ?>
									<?php } ?>

									<?php if ( $found_field ) { ?>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
						<?php } ?>

					</div>
					<?php
					do_action( 'user_registration_edit_profile_form' );
					$submit_btn_class = apply_filters( 'user_registration_form_update_btn_class', array() );
					?>
					<p>
						<?php wp_nonce_field( 'save_profile_details' ); ?>
						<input type="submit" class="user-registration-Button button <?php echo esc_attr( implode( ' ', $submit_btn_class ) ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'user-registration' ); ?>" />
						<input type="hidden" name="action" value="save_profile_details" />
					</p>
				</div>
			</div>

		</div>
	</form>
</div>

<?php do_action( 'user_registration_after_edit_profile_form' ); ?>
