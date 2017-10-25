/**
 * Customizer Events Communicator.
 */
( function ( exports, $ ) {
	"use strict";

	var api = wp.customize, OldPreviewer;

	var all_controls = {
		'logo' : [
			'login_designer[logo_title]',
			'login_designer[logo]',
			'login_designer[logo_margin_bottom]',
			'login_designer_admin[logo_url]',
			'login_designer[disable_logo]',
		],
		'form' : [
			'login_designer[form_title]',
			'login_designer[form_width]',
			'login_designer[form_radius]',
			'login_designer[form_bg]',
			'login_designer[form_vertical_padding]',
			'login_designer[form_side_padding]',
			'login_designer[form_shadow]',
			'login_designer[form_shadow_opacity]',
		],
		'fields' : [
			'login_designer[fields_title]',
			'login_designer[field_bg]',
			'login_designer[field_border]',
			'login_designer[field_border_color]',
			'login_designer[field_radius]',
			'login_designer[field_side_padding]',
			'login_designer[field_shadow]',
			'login_designer[field_shadow_opacity]',
			'login_designer[field_shadow_inset]',
			'login_designer[field_text_title]',
			'login_designer[field_font]',
			'login_designer[field_font_size]',
			'login_designer[field_color]',
		],
		'labels' : [
			'login_designer[labels_title]',
			'login_designer[label_font]',
			'login_designer[label_font_size]',
			'login_designer[label_color]',
			'login_designer[username_label]',
			'login_designer[password_label]',
		],
		'button' : [
			'login_designer[button_title]',
		],
		'background' : [
			'login_designer[bg_title]',
			'login_designer[bg_image]',
			'login_designer[bg_color]',
			'login_designer[bg_repeat]',
			'login_designer[bg_size]',
			'login_designer[bg_attach]',
			'login_designer[bg_position]',
			'login_designer[bg_image_gallery]',
		],
	};

	function active_control( section ) {

		all_controls.logo.forEach(function(item, index, array) {
			control_visibility( all_controls.logo, 'deactivate' );
		});

		all_controls.form.forEach(function(item, index, array) {
			control_visibility( all_controls.form, 'deactivate' );
		});

		all_controls.fields.forEach(function(item, index, array) {
			control_visibility( all_controls.fields, 'deactivate' );
		});

		all_controls.labels.forEach(function(item, index, array) {
			control_visibility( all_controls.labels, 'deactivate' );
		});

		all_controls.button.forEach(function(item, index, array) {
			control_visibility( all_controls.button, 'deactivate' );
		});

		all_controls.background.forEach(function(item, index, array) {
			control_visibility( all_controls.background, 'deactivate' );
		});

		control_visibility( section, 'activate' );
	}

	function control_visibility( controls, action ) {

		controls.forEach( function( item, index, array ) {

			if ( action == 'activate' ) {

				// For this particular control, let's check to see if corresponding options are visible.
				// We only want to show relevant options based on the user's contextual design decisions.
				if ( item === 'login_designer[logo_margin_bottom]' ) {

					wp.customize( 'login_designer[logo]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( setting.get() ) {
									// If there is a custom logo uploaded, let's show the bottom positioning option.
									wp.customize.control( item ).activate( { duration: 0 } );
								} else {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

					wp.customize( 'login_designer[disable_logo]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( true === setting.get() ) {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});


				} else if ( item === 'login_designer[logo]' ) {

					wp.customize( 'login_designer[disable_logo]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( true === setting.get() ) {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								} else {
									// If there's no custom background image, let's show the gallery.
									wp.customize.control( item ).activate( { duration: 0 } );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else if ( item === 'login_designer[logo_title]' ) {

					wp.customize( 'login_designer[disable_logo]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( true === setting.get() ) {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								} else {
									// If there's no custom background image, let's show the gallery.
									wp.customize.control( item ).activate( { duration: 0 } );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else if ( item === 'login_designer_admin[logo_url]' ) {

					wp.customize( 'login_designer[disable_logo]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( true === setting.get() ) {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								} else {
									// If there's no custom background image, let's show the gallery.
									wp.customize.control( item ).activate( { duration: 0 } );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else if ( item === 'login_designer[bg_image_gallery]' ) {

					wp.customize( 'login_designer[bg_image]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( ! setting.get() ) {
									// If there's no custom background image, let's show the gallery.
									wp.customize.control( item ).activate( { duration: 0 } );
								} else {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else if ( item === 'login_designer[bg_repeat]' ) {

					$.each( [ 'login_designer[bg_image]', 'login_designer[bg_image_gallery]' ], function( index, settingId ) {

						wp.customize( settingId, function( setting ) {
							wp.customize.control( item, function( control ) {
								var visibility = function() {

									if ( setting.get() && 'none' !== setting.get() ) {
										// If there is a background image or gallery image, but neither are set to "none".
										wp.customize.control( item ).activate( { duration: 0 } );
									} else {
										// If not, let's quickly hide it.
										control.container.slideUp( 0 );
									}
								};

								visibility();
								setting.bind( visibility );
							} );
						} );
					} );

				} else if ( item === 'login_designer[bg_size]' ) {

					$.each( [ 'login_designer[bg_image]', 'login_designer[bg_image_gallery]' ], function( index, settingId ) {

						wp.customize( settingId, function( setting ) {
							wp.customize.control( item, function( control ) {
								var visibility = function() {

									if ( setting.get() && 'none' !== setting.get() ) {
										// If there is a background image or gallery image, but neither are set to "none".
										wp.customize.control( item ).activate( { duration: 0 } );
									} else {
										// If not, let's quickly hide it.
										control.container.slideUp( 0 );
									}
								};

								visibility();
								setting.bind( visibility );
							} );
						} );
					} );

				} else if ( item === 'login_designer[bg_attach]' ) {

					$.each( [ 'login_designer[bg_image]', 'login_designer[bg_image_gallery]' ], function( index, settingId ) {

						wp.customize( settingId, function( setting ) {
							wp.customize.control( item, function( control ) {
								var visibility = function() {

									if ( setting.get() && 'none' !== setting.get() ) {
										// If there is a background image or gallery image, but neither are set to "none".
										wp.customize.control( item ).activate( { duration: 0 } );
									} else {
										// If not, let's quickly hide it.
										control.container.slideUp( 0 );
									}
								};

								visibility();
								setting.bind( visibility );
							} );
						} );
					} );

				} else if ( item === 'login_designer[bg_position]' ) {

					$.each( [ 'login_designer[bg_image]', 'login_designer[bg_image_gallery]' ], function( index, settingId ) {

						wp.customize( settingId, function( setting ) {
							wp.customize.control( item, function( control ) {
								var visibility = function() {

									if ( setting.get() && 'none' !== setting.get() ) {
										// If there is a background image or gallery image, but neither are set to "none".
										wp.customize.control( item ).activate( { duration: 0 } );
									} else {
										// If not, let's quickly hide it.
										control.container.slideUp( 0 );
									}
								};

								visibility();
								setting.bind( visibility );
							} );
						} );
					} );

				} else if ( item === 'login_designer[form_shadow_opacity]' ) {

					wp.customize( 'login_designer[form_shadow]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( '0' < setting.get() ) {
									// If there is a custom logo uploaded, let's show the bottom positioning option.
									wp.customize.control( item ).activate( { duration: 0 } );
								} else {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else if ( item === 'login_designer[field_shadow_opacity]' ) {

					wp.customize( 'login_designer[field_shadow]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( '0' < setting.get() ) {
									// If there is a custom logo uploaded, let's show the bottom positioning option.
									wp.customize.control( item ).activate( { duration: 0 } );
								} else {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else if ( item === 'login_designer[field_shadow_inset]' ) {

					wp.customize( 'login_designer[field_shadow]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( '0' < setting.get() ) {
									// If there is a custom logo uploaded, let's show the bottom positioning option.
									wp.customize.control( item ).activate( { duration: 0 } );
									console.log( 'has a shadow' );
								} else {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
									console.log( 'no shadow' );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				}

				else if ( item === 'login_designer[field_border_color]' ) {

					wp.customize( 'login_designer[field_border]', function( setting ) {
						wp.customize.control( item, function( control ) {
							var visibility = function() {

								if ( '0' < setting.get() ) {
									// If there is a custom logo uploaded, let's show the bottom positioning option.
									wp.customize.control( item ).activate( { duration: 0 } );
									console.log( 'border' );
								} else {
									// If not, let's quickly hide it.
									control.container.slideUp( 0 );
									console.log( 'no border' );
								}
							};

							visibility();
							setting.bind( visibility );
						});
					});

				} else {
					// Activate all others.
					wp.customize.control( item ).activate( { duration: 0 } );
				}

			} else {
				wp.customize.control( item ).deactivate( { duration: 0 } );
			}
		});
	}

	//  Customizer Previewer
	api.myCustomizerPreviewer = {

		init: function () {

			var
			self = this,
			active_state,
			logo_event  		= 'login-designer-edit-logo',
			form_event  		= 'login-designer-edit-loginform',
			fields_event 		= 'login-designer-edit-loginform-fields',
			username_label_event 	= 'login-designer-edit-loginform-labels-username',
			password_label_event 	= 'login-designer-edit-loginform-labels-password',
			button_event 		= 'login-designer-edit-button',
			background_event 	= 'login-designer-edit-background';

			// Function used for contextually aware Customizer options.
			function bind_control_visibility_event( event, active_controls, focus_control ) {

				api.myCustomizerPreviewer.preview.bind( event, function() {

					//If the current event is active, there's no need to run it.
					// if ( active_state !== event ) {

						// Visibility.
						active_control( active_controls );

						// Focus.
						wp.customize.control( focus_control ).focus();

					// }

					// active_state = event;

					// For debugging purposes.
					// console.log( active_state );

				} );
			}

			// Only show visible options when necessary.
			bind_control_visibility_event( logo_event, all_controls.logo, 'login_designer[logo]' );
			bind_control_visibility_event( form_event, all_controls.form, 'login_designer[form_title]' );
			bind_control_visibility_event( fields_event, all_controls.fields, 'login_designer[form_title]' );
			bind_control_visibility_event( username_label_event, all_controls.labels, 'login_designer[username_label]' );
			bind_control_visibility_event( password_label_event, all_controls.labels, 'login_designer[password_label]' );
			bind_control_visibility_event( button_event, all_controls.buttons, 'login_designer[button_title]' );
			bind_control_visibility_event( background_event, all_controls.background, 'login_designer[bg_title]' );

			// Open settings panel when the settings icon is clicked.
			this.preview.bind( 'login-designer-edit-settings', function() {
				var section = wp.customize.section( 'login_designer__section--settings' );
				if ( ! section.expanded() ) {
					section.expand( { duration: 0 } );
				}
			} );

			// Open settings panel when the settings icon is clicked.
			this.preview.bind( 'login-designer-edit-template', function() {
				var section = wp.customize.section( 'login_designer__section--templates' );
				if ( ! section.expanded() ) {
					section.expand( { duration: 0 } );
				}
			} );

		}
	};

	/**
	 * Capture the instance of the Preview since it is private.
	 */
	OldPreviewer = api.Previewer;
	api.Previewer = OldPreviewer.extend( {
		initialize: function( params, options ) {

			// Store a reference to the Previewer
			api.myCustomizerPreviewer.preview = this;

			// Call the old Previewer's initialize function
			OldPreviewer.prototype.initialize.call( this, params, options );
		}
	} );

	$( function() {
		// Initialize our Previewer
		api.myCustomizerPreviewer.init();
	} );

} )( wp, jQuery );
