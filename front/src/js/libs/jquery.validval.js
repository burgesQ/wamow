/*	
 *	jQuery validVal version 5.0.2
 *	demo's and documentation:
 *	validval.frebsite.nl
 *
 *	Copyright (c) 2013 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */


(function( $ )
{

	var _PLUGIN_	= 'validVal',
		_FIELD_		= 'validValField',
		_VERSION_	= '5.0.2',
		_INPUTS_ 	= 'textarea, select, input:not( [type="button"], [type="submit"], [type="reset"] )';


	//	validVal already excists
	if ( $.fn[ _PLUGIN_ ] )
	{
		return;
	}


	function ValidVal( form, opts )
	{
		this.form = form;
		this.opts = $.extend( true, {}, $.fn[ _PLUGIN_ ].defaults, opts );

		this._gatherValidation();
		this._bindEvents();
		this._bindCustomEvents();
		this.addField( this.opts.validate.fields.filter( $(_INPUTS_, this.form) ) );
	}
	ValidVal.prototype = {

		//	Public methods
		addField: function( $field )
		{
			if ( isHtmlElement( $field ) || typeof $field == 'string' )
			{
				$field = $( $field );
			}
			if ( !( $field instanceof $ ) )
			{
				$.fn[ _PLUGIN_ ].debug( 'Not a valid argument for "$field"' );
				return this;
			}

			var that = this;

			return $field.each(
				function()
				{
					var $f = $(this),
						vf = $f.data( _FIELD_ );

					if ( vf )
					{
						vf.destroy();
					}
					$f.data( _FIELD_, new ValidValField( $f, that ) );
				}
			);
		},
		validate: function( body, callCallback )
		{
			var that = this;

			//	Complement arguments
			if ( typeof body == 'undefined' )
			{
				body = this.form;
				callCallback = true;
			}
			else if ( typeof callCallback != 'boolean' )
			{
				callCallback = false;
			}

			if ( typeof this.opts.form.onValidate == 'function' )
			{
				this.opts.form.onValidate.call( this.form[ 0 ], this.opts.language );
			}

			//	Set varialbes
			var miss_arr = $(),
				data_obj = {};

			//	Validate fields
			this.opts.validate.fields.filter( $(_INPUTS_, body) ).each(
				function()
				{
					var $f = $(this),
						vf = $f.data( _FIELD_ );

					if ( vf )
					{
						vf.validate( that.opts.validate.onSubmit )
						if ( vf.valid )
						{
							var v = $f.val();
							if ( $f.is( '[type="radio"]' ) || $f.is( '[type="checkbox"]' ) )
							{
								if ( !$f.is( ':checked' ) )
								{
									v = '';
								}
							}
							if ( typeof v == 'undefined' || v == null )
							{
								v = '';
							}
							data_obj[ $f.attr( 'name' ) ] = v;
						}
						else
						{
							if ( that.opts.validate.onSubmit !== false )
							{
								miss_arr = miss_arr.add( $f );
							}
						}
					}
				}
			);

			//	Not valid
			if ( miss_arr.length > 0 )
			{
				if ( typeof this.opts.form.onInvalid == 'function' && callCallback )
				{
					this.opts.form.onInvalid.call( this.form[ 0 ], miss_arr, this.opts.language );
				}
				return false;
			}

			//	Valid
			else
			{
				if ( typeof this.opts.form.onValid == 'function' && callCallback )
				{
					this.opts.form.onValid.call( this.form[ 0 ], this.opts.language );
				}
				return data_obj;
			}
		},
		submitForm: function()
		{
			var result = this.validate();
			if ( result )
			{
				this.opts.validate.fields.filter( $(_INPUTS_, this.form) ).each(
					function()
					{
						var vf = $(this).data( _FIELD_ );
						if ( vf )
						{
							vf.clearPlaceholderValue();
						}
					}
				);
			}
			return result;
		},
		resetForm: function()
		{
			var that = this;

			if ( typeof this.opts.form.onReset == 'function' )
			{
				this.opts.form.onReset.call( this.form[ 0 ], this.opts.language );
			}

			this.opts.validate.fields.filter( $(_INPUTS_, this.form) ).each(
				function()
				{
					var $f = $(this),
						vf = $f.data( _FIELD_ );

					if ( vf )
					{
						if ( vf.placeholderValue !== false )
						{
							$f.addClass( 'inactive' );
							$f.val( vf.placeholderValue );
						}
						else
						{
							$f.val( vf.originalValue );
						}
						vf.isValid( true, true );
					}
				}
			);
			return true;
		},
		options: function( o )
		{
			if ( typeof o == 'object' )
			{
				this.opts = $.extend( this.opts, o );
			}
			return this.opts;
		},
		destroy: function()
		{
			this.form.unbind( '.vv' );
			this.form.data( _PLUGIN_, null );
			this.opts.validate.fields.filter( $(_INPUTS_, this.form) ).each(
				function()
				{
					var vf = $(this).data( _FIELD_ );
					if ( vf )
					{
						vf.destroy();
					}
				}
			);
			return true;
		},

		//	Protected methods
		_gatherValidation: function()
		{
			this.opts.validations = {};
			if ( $.fn[ _PLUGIN_ ].customValidations )
			{
				this.opts.validations = $.extend( this.opts.validations, $.fn[ _PLUGIN_ ].customValidations );
			}
			if ( this.opts.customValidations )
			{
				this.opts.validations = $.extend( this.opts.validations, this.opts.customValidations );
			}
			this.opts.validations = $.extend( this.opts.validations, $.fn[ _PLUGIN_ ].defaultValidations );
			return this;
		},
		_bindEvents: function ()
		{
			var that = this;

			if ( this.form.is( 'form' ) )
			{
				this.form.attr( 'novalidate', 'novalidate' );
				this.form.bind(
					namespace( 'submit' ),
					function( event, validate )
					{
						if ( typeof validate != 'boolean' )
						{
							validate = true;
						}
						return ( validate )
							? that.submitForm()
							: true;
					}
				);
				this.form.bind(
					namespace( 'reset' ),
					function( event )
					{
						return that.resetForm();
					}
				);
			}
			return this;
		},
		_bindCustomEvents: function()
		{
			var that = this;

			this.form.bind(
				namespace([
					'addField',
					'destroy',
					'validate',
					'submitForm',
					'resetForm',
					'options'
				]),
				function()
				{
					arguments = Array.prototype.slice.call( arguments );
					var event = arguments.shift(),
						type = event.type;

					event.stopPropagation();

					if ( typeof that[ type ] != 'function' )
					{
						$.fn.validVal.debug( 'Public method "' + type + '" not found.' );
						return false;
					}
					return that[ type ].apply( that, arguments );
				}
			);
			return this;
		}
	};



	function ValidValField( field, form )
	{
		this.field	= field;
		this.form  	= form;

		this.originalValue = this.field.attr( 'value' ) || '';

		this._gatherValidations();
		this._bindEvents();
		this._bindCustomEvents();
		this._init();
	}

	ValidValField.prototype = {
	
		//	Public methods
		
		validate: function( onEvent, fixPlaceholder )
		{
			var that = this;

			if ( onEvent === false )
			{
				return;
			}
			if ( typeof fixPlaceholder != 'boolean' )
			{
				fixPlaceholder = true;
			}

			this.valid = true;

			if ( ( this.field.is( ':hidden' ) && !this.form.opts.validate.fields.hidden ) ||
				( this.field.is( ':disabled' ) && !this.form.opts.validate.fields.disabled )
			) {
				return true;
			}

			if ( fixPlaceholder )
			{
				this.clearPlaceholderValue();
			}
			if ( typeof this.form.opts.fields.onValidate == 'function' )
			{
				this.form.opts.fields.onValidate.call( this.field[ 0 ], this.form.form, this.form.opts.language );
			}

			var invalid_check = false,
				val = trim( this.field.val() );

			for ( var v in this.form.opts.validations )
			{
				var f = this.form.opts.validations[ v ];
				if ( typeof f == 'function' && $.inArray( v, this.validations ) != -1 )
				{
					if ( !f.call( this.field[ 0 ], val ) )
					{
						invalid_check = v;
						break;
					}
				}
			}

			this.valid = ( invalid_check ) ? false : true;
			var callCallback = ( this.valid )
				? ( onEvent !== 'invalid' )
				: ( onEvent !== 'valid' );

			this.isValid( this.valid, callCallback, invalid_check );

			if ( this.validationgroup !== false )
			{
				$(_INPUTS_).not( this.field ).each(
					function()
					{
						var vf = $(this).data( _FIELD_ );
						if ( vf && vf.validationgroup == that.validationgroup )
						{
							vf.isValid( that.valid, true );
						}
					}
				);
			}

			if ( fixPlaceholder )
			{
				this.restorePlaceholderValue();
			}
			if ( invalid_check )
			{
				$.fn[ _PLUGIN_ ].debug( 'invalid validation: ' + invalid_check );
			}
			return this.valid;
		},
		isValid: function( valid, callCallback )
		{
			if ( typeof valid == 'boolean' )
			{
				this.valid = valid;
				if ( callCallback )
				{
					var fn = ( valid ) ? 'onValid' : 'onInvalid';
					if ( typeof this.form.opts.fields[ fn ] == 'function' )
					{
						this.form.opts.fields[ fn ].call( this.field[ 0 ], this.form.form, this.form.opts.language );
					}
				}
			}
			return this.valid;
		},
		getValidations: function()
		{
			return this.validations;
		},
		setValidations: function( validations )
		{
			if ( typeof validations == 'string' )
			{
				this.validations = validations.split( ' ' );
			}
			else if ( validations instanceof Array )
			{
				this.validations = validations;
			}
			else
			{
				$.fn.validVal.debug( 'Argument "validations" should be an array.' );
			}
			return this.validations;
		},
		addValidation: function( validation )
		{
			if ( typeof validation == 'string' )
			{
				validation = validation.split( ' ' );
			}
			for( var v in validation )
			{
				this.validations.push( validation[ v ] );
			}
			return this.validations;
		},
		removeValidation: function( validation )
		{
			if ( typeof validation == 'string' )
			{
				validation = validation.split( ' ' );
			}
			for( var v in validation )
			{
				var pos = $.inArray( validation[ v ], this.validations );
				if ( pos != -1 )
				{
					this.validations.splice( pos, 1 );
				}
			}
			return this.validations;
		},
		clearPlaceholderValue: function()
		{
			this._togglePlaceholderValue( 'clear' );
			return true;
		},
		restorePlaceholderValue: function()
		{
			this._togglePlaceholderValue( 'restore' );
			return true;
		},
		destroy: function()
		{
			this.field.unbind( '.vv' );
			this.field.data( _FIELD_, null );
			return true;
		},
	
		//	Protected methods
		_gatherValidations: function()
		{
			this.autotab = false;
			this.corresponding = false;
			this.requiredgroup = false;
			this.validationgroup = false;
			this.placeholderValue = false;
			this.placeholderNumber = false;
			this.passwordplaceholder = false;

			this.validations = [];

			if ( this.field.is( 'select' ) )
			{
				this.originalValue = this.field.find( 'option:first' ).attr( 'value' ) || '';
			}
			else if ( this.field.is( 'textarea' ) )
			{
				this.originalValue = this.field.text();
			}


			//	Refactor HTML5 usage
			if ( this.form.opts.supportHtml5 )
			{

				var valids = this.field.data( 'vv-validations' );
				if ( valids )
				{
					this.validations.push( valids );
					this.__removeAttr( 'data-vv-validations' );
				}
	
				//	Placeholder attribute, only use if placeholder not supported by browser or placeholder not in keepAttributes-option
				if ( this.__hasHtml5Attr( 'placeholder' ) && this.field.attr( 'placeholder' ).length > 0 )
				{
					if ( !$.fn[ _PLUGIN_ ].support.placeholder || $.inArray( 'placeholder', this.form.opts.keepAttributes ) == -1 )
					{
						this.placeholderValue = this.field.attr( 'placeholder' );
					}
				}

				if ( this.placeholderValue !== false )
				{
					this.__removeAttr( 'placeholder' );
				}

				//	Pattern attribute
				if ( this.__hasHtml5Attr( 'pattern' ) && this.field.attr( 'pattern' ).length > 0 )
				{
					this.pattern = this.field.attr( 'pattern' );
					this.validations.push( 'pattern' );
					this.__removeAttr( 'pattern' );
				}

				//	Corresponding, required group and validation group
				var dts = [ 'corresponding', 'requiredgroup', 'validationgroup' ];
				for ( var d = 0, l = dts.length; d < l; d++ )
				{
					var dt = this.field.data( 'vv-' + dts[ d ] );
					if ( dt )
					{
						this[ dts[ d ] ] = dt;
						this.validations.push( dts[ d ] );
						this.__removeAttr( 'data-vv-' + dts[ d ] );
					}
				}

				// Attributes
				var atr = [ 'required', 'autofocus' ];
				for ( var a = 0, l = atr.length; a < l; a++ )
				{
					if ( this.__hasHtml5Attr( atr[ a ] ) )
					{
						this.validations.push( atr[ a ] );
						this.__removeAttr( atr[ a ] );
					}
				}

				//	Type-values
				var typ = [ 'number', 'email', 'url' ];
				for ( var t = 0, l = typ.length; t < l; t++ )
				{
					if ( this.__hasHtml5Type( typ[ t ] ) )
					{
						this.validations.push( typ[ t ] );
					}
				}
				
				//	Autotab
				if ( this.field.data( 'vv-autotab' ) )
				{
					this.autotab = true;
					this.__removeAttr( 'data-vv-autotab' );
				}
			}


			//	Refactor non-HTML5 usage
			var classes = this.field.attr( 'class' );
			if ( classes && classes.length )
			{

				//	Placeholder
				if ( this.field.hasClass( 'placeholder' ) )
				{
					if ( this.field.is( 'select' ) )
					{
						var num = 0,
							opt = this.field.data( 'vv-placeholder-number' );

						if ( opt )
						{
							num = opt;
							this.__removeAttr( 'data-vv-placeholder-number' );
						}
						else if ( typeof this.form.opts.selectPlaceholder == 'number' )
						{
							num = this.form.opts.selectPlaceholder;
						}
						else
						{
							var $options = this.field.find( 'option' ),
								selected = $options.index( $options.filter( '[selected]' ) );

							if ( selected > -1 )
							{
								num = selected;
							}
						}
						this.placeholderNumber = num;
						this.originalValue = this.field.find( 'option:eq( ' + num + ' )' ).attr( 'value' ) || '';
					}
					this.placeholderValue = this.originalValue;
					this.originalValue = '';
					this.__removeClass( 'placeholder' );
				}

				//	Corresponding
				var corsp = 'corresponding:',
					start = classes.indexOf( corsp );
	
				if ( start != -1 )
				{
					var corrcls = classes.substr( start ).split( ' ' )[ 0 ],
						corresp = corrcls.substr( corsp.length );
	
					if ( corresp.length )
					{
						this.corresponding = corresp;
						this.validations.push( 'corresponding' );
						this.field.removeClass( corrcls );
					}
				}
	
				//	Pattern
				//		still uses alt-attribute...
				if ( this.field.hasClass( 'pattern' ) )
				{
					this.pattern = this.field.attr( 'alt' ) || '';
					this.validations.push( 'pattern' );
					this.__removeAttr( 'alt' );
					this.__removeClass( 'pattern' );
				}
	
				//	Groups
				var grp = [ 'requiredgroup', 'validationgroup' ];
				for ( var g = 0, l = grp.length; g < l; g++ )
				{
					var group = grp[ g ] + ':',
						start = classes.indexOf( group );
	
					if ( start != -1 )
					{
						var groupclass	= classes.substr( start ).split( ' ' )[ 0 ],
							groupname 	= groupclass.substr( group.length );
	
						if ( groupname.length )
						{
							this[ grp[ g ] ] = groupname;
							this.validations.push( grp[ g ]);
							this.field.removeClass( groupclass );
						}
					}
				}

				//	Autotab
				if ( this.field.hasClass( 'autotab' ) )
				{
					this.autotab = true;
					this.__removeClass( 'autotab' );
				}
			}
	
			//	Password placeholder
			if ( this.placeholderValue !== false && this.field.is( '[type="password"]' ) )
			{
				this.passwordplaceholder = true;
			}


			//	Add all remaining classes
			var classes = this.field.attr( 'class' );
			if ( classes && classes.length )
			{
				this.validations.push( classes );
			}

			this.validations = unique( this.validations.join( ' ' ).split( ' ' ) );

			return this;
		},
		_bindEvents: function()
		{
			var that = this;

			this.field.bind(
				namespace( 'focus' ),
				function( event )
				{
					$(this).addClass( 'focus' );
					that.clearPlaceholderValue();
				}
			);
			this.field.bind(
				namespace( 'blur' ),
				function( event )
				{
					$(this).removeClass( 'focus' );
					that.validate( that.form.opts.validate.onBlur );
				}
			);

			this.field.bind(
				namespace( 'keyup' ),
				function( event )
				{
					if ( !preventkeyup( event.keyCode ) )
					{
						that.validate( that.form.opts.validate.onKeyup, false );
					}
				}
			);

			if ( this.field.is( 'select, input[type="checkbox"], input[type="radio"]' ) )
			{
				this.field.bind(
					namespace( 'change' ),
					function( event )
					{
						$(this).trigger( namespace( 'blur' ) );
					}
				);	
			}
			return this;
		},
		_bindCustomEvents: function()
		{
			var that = this;

			this.field.bind(
				namespace([
					'validate',
					'isValid',
					'destroy',
					'addValidation',
					'removeValidation'
				]),
				function()
				{
					arguments = Array.prototype.slice.call( arguments );
					var event = arguments.shift(),
						type = event.type;

					event.stopPropagation();

					if ( typeof that[ type ] != 'function' )
					{
						$.fn.validVal.debug( 'Public method "' + type + '" not found.' );
						return false;
					}
					return that[ type ].apply( that, arguments );
				}
			);
			this.field.bind(
				namespace([ 'validations' ]),
				function( event, validations, callCallback )
				{
					if ( typeof validations == 'undefined' )
					{
						return this.getValidations();
					}
					else
					{
						return this.setValidations( validations, callCallback );
					}
				}
			);
			return this;
		},
		_init: function()
		{
			var that = this;

			//	Placeholder
			if ( this.placeholderValue !== false )
			{
				if ( this.field.val() == '' )
				{
					this.field.val( this.placeholderValue );
				}
				if ( this.passwordplaceholder )
				{
					if ( this.field.val() == this.placeholderValue ) try
					{
						this.field[ 0 ].type = 'text';
					}
					catch( err ) {};
				}
				if ( this.field.val() == this.placeholderValue )
				{
					this.field.addClass( 'inactive' );
				}
				if ( this.field.is( 'select' ) )
				{
					this.field.find( 'option:eq(' + this.placeholderNumber + ')' ).addClass( 'inactive' );
					this.field.bind(
						namespace( 'change' ),
						function( event )
						{
							$(this)[ that.field.val() == that.placeholderValue ? 'addClass' : 'removeClass' ]( 'inactive' );
						}
					);
				}
			}

			//	Corresponding
			if ( this.corresponding !== false )
			{
				$(_INPUTS_).filter('[name="' + this.corresponding + '"]').bind(
					namespace( 'blur' ),
					function( event )
					{
						that.validate( that.form.opts.validate.onBlur );
					}
				).bind(
					namespace( 'keyup' ),
					function( event )
					{
						if ( !preventkeyup( event.keyCode ) )
						{
							that.validate( that.form.opts.validate.onKeyup, false );
						}
					}
				);
			}

			//	Autotabbing
			if ( this.autotab )
			{
				var max = this.field.attr( 'maxlength' ),
					tab = this.field.attr( 'tabindex' ),
					$next = $(_INPUTS_).filter('[tabindex="' + ( parseInt( tab ) + 1 ) + '"]');
	
				if ( this.field.is( 'select' ) )
				{
					if ( tab )
					{
						this.field.bind(
							namespace( 'change' ),
							function( event )
							{
								if ( $next.length )
								{
									$next.focus();
								}
							}
						);
					}
				}
				else
				{
					if ( max && tab )
					{
						this.field.bind(
							namespace( 'keyup' ),
							function( event )
							{
								if ( $(this).val().length == max )
								{
									if ( !preventkeyup( event.keyCode ) )
									{
										$(this).trigger( namespace( 'blur' ) );
										if ( $next.length )
										{
											$next.focus();
										}
									}
								}
							}
						);
					}
				}
			}
	
			//	Autofocus
			if ( $.inArray( 'autofocus', this.validations ) != -1 && !this.field.is( ':disabled' ) )
			{
				this.field.focus();
			}
			return this;
		},
		_togglePlaceholderValue: function( toggle )
		{
			if ( this.placeholderValue !== false )
			{
				if ( toggle == 'clear' )
				{
					var v1 = this.placeholderValue,
						v2 = '',
						cl = 'removeClass',
						tp = 'password';
				}
				else
				{
					var v1 = '',
						v2 = this.placeholderValue,
						cl = 'addClass',
						tp = 'text';
				}
				if ( this.field.val() == v1 && !this.field.is( 'select' )  )
				{
					this.field.val( v2 );
					this.field[ cl ]( 'inactive' );

					if ( this.passwordplaceholder ) try
					{
						this.field[ 0 ].type = tp;
					}
					catch( err ) {};
				}
			}
			return this;
		},

		//	Private methods
		__hasHtml5Attr: function( a )
		{
			// non HTML5 browsers
			if ( typeof this.field.attr( a ) == 'undefined' )
			{
				return false;
			}
			// HTML5 browsers
			if ( this.field.attr( a ) === 'false' || this.field.attr( a ) === false )
			{
				return false;
			}
			return true;
		},
		__hasHtml5Type: function( t )
		{
			// cool HTML5 browsers
			if ( this.field.attr( 'type' ) == t )
			{
				return true;
			}
	
			// non-HTML5 but still cool browsers
			if ( this.field.is( 'input[type="' + t + '"]' ) )
			{
				return true;
			}
	
			//	non-HTML5, non-cool browser
			var $c = $( '<div />' ).append( this.field.clone() ).html()
			if ( $c.indexOf( 'type="' + t + '"' ) != -1 || $c.indexOf( 'type=\'' + t + '\'' ) != -1 || $c.indexOf( 'type=' + t + '' ) != -1 )
			{
				return true;
			}
			return false;
		},
		__removeAttr: function( a )
		{
			if ( $.inArray( a, this.form.opts.keepAttributes ) == -1 )
			{
				this.field.removeAttr( a );
			}
			return this;
		},
		__removeClass: function( c )
		{
			if ( $.inArray( c, this.form.opts.keepClasses ) == -1 )
			{
				this.field.removeClass( c );
			}
			return this;
		}
	};



	$.fn[ _PLUGIN_ ] = function( o, c )
	{
		return this.each(
			function()
			{
				var $t = $(this);
				if ( $t.data( _PLUGIN_ ) )
				{
					$t.data( _PLUGIN_ ).destroy();
				}
				$t.data( _PLUGIN_, new ValidVal( $t, o, c ) );
			}
		);
	};

	$.fn[ _PLUGIN_ ].version = _VERSION_;

	$.fn[ _PLUGIN_ ].defaults = {
		'selectPlaceholder'	: 0,
		'supportHtml5'		: true,
		'language'			: 'en',
		'customValidations'	: {},
		'validate'	: {
			'onBlur'	: true,
			'onSubmit'	: true,
			'onKeyup'	: false,
			'fields'	: {
				'hidden'	: false,
				'disabled'	: false,
				'filter'	: function( $i )
				{
					return $i;
				}
			}
		},
		'fields'	: {
			'onValidate'	: null,
			'onValid'		: function()
			{
				$(this).add( $(this).parent() ).removeClass( 'invalid' );
				if($(this).hasClass('vv-gparent'))
					$(this).add( $(this).parent().parent() ).removeClass( 'gp-invalid' );
			},
			'onInvalid'		: function()
			{
				$(this).add( $(this).parent() ).addClass( 'invalid' );
				if($(this).hasClass('vv-gparent'))
					$(this).add( $(this).parent().parent() ).addClass( 'gp-invalid' );
			}
		},
		'form'	: {
			'onReset'	: null,
			'onValidate': null,
			'onValid'	: null,
			'onInvalid'	: function( fieldArr, language )
			{
				switch ( language )
				{
					case 'nl':
						msg = 'Let op, niet alle velden zijn correct ingevuld.';
						break;

					case 'de':
						msg = 'Achtung, nicht alle Felder sind korrekt ausgefuellt.';
						break;

					case 'es':
						msg = 'Atención, no se han completado todos los campos correctamente.';
						break;

					case 'en':
					default:
						msg = 'Attention, not all fields have been filled out correctly.';
						break;
				}
				alert( msg );
				fieldArr.first().focus();
			}
		},
		'keepClasses'	: [ 'required' ],
		'keepAttributes': [ 'pattern', 'placeholder' ]
	};

	$.fn[ _PLUGIN_ ].defaultValidations = {
		'required': function( v )
		{
			var $f = $(this);

			if ( $f.is( '[type="radio"]' ) || $f.is( '[type="checkbox"]' ) )
			{
				if ( $f.is( '[type="radio"]' ) )
				{
					var name = $f.attr( 'name' );
					if ( name && name.length > 0 )
					{
						$f = $( 'input[name="' + name + '"]' );
					}
				}
				if ( !$f.is( ':checked' ) )
				{
					return false;
				}

			}
			else if ( $f.is( 'select' ) )
			{
				var vf = $f.data( _FIELD_ );
				if ( vf && vf.placeholderValue !== false )
				{
					if ( $f.val() == vf.placeholderValue )
					{
						return false;
					}
				}
				else
				{
					if ( v.length == 0 )
					{
						return false;
					}
				}
			}
			else
			{
				if ( v.length == 0 )
				{
					return false;
				}
			}
			return true;
		},
		'Required': function( v )
		{
			return $.fn[ _PLUGIN_ ].defaultValidations.required.call( this, v );
		},
		'requiredgroup': function( v )
		{
			var $f = $(this),
				vf = $f.data( _FIELD_ );

			if ( vf && vf.requiredgroup !== false )
			{
				$f = $();
				$(_INPUTS_).each(
					function()
					{
						var tf = $(this).data( _FIELD_ );
						if ( tf &&  tf.requiredgroup == vf.requiredgroup )
						{
							$f = $f.add( this );
						}
					}
				);
			}

			var result = false;
			$f.each(
				function()
				{
					var f = this;
					if ( $.fn[ _PLUGIN_ ].defaultValidations.required.call( f, trim( $(f).val() ) ) )
					{
						result = true;
					}
				}
			);
			return result;
		},
		'corresponding': function( v )
		{
			var org = '',
				vf = $(this).data( _FIELD_ );

			if ( vf && vf.corresponding !== false )
			{
				var $f = $(_INPUTS_).filter('[name="' + vf.corresponding + '"]'),
					vf = $f.data( _FIELD_ );

				if ( vf )
				{
					vf.clearPlaceholderValue();
					org = trim( $f.val() );
					vf.restorePlaceholderValue();
				}
				return ( v == org );
			}
			return false;
		},
		'number': function( v )
		{
			v = stripWhitespace( v );
			if ( v.length == 0 )
			{
				return true;
			}
			if ( isNaN( v ) )
			{
				return false;
			}
			return true;
		},
		'email': function( v )
		{
			if ( v.length == 0 )
			{
				return true;
			}
			var r = /^([a-zA-Z0-9_\.\-+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			return r.test( v );
		},
		'url': function( v )
		{
			if ( v.length == 0 )
			{
				return true;
			}
			if ( v.match(/^www\./) )
			{
				v = "http://" + v;
			}
			return v.match(/^(http\:\/\/|https\:\/\/)(.{4,})$/);
		},
		'pattern': function( v )
		{
			if ( v.length == 0 )
			{
				return true;
			}
			var $f = $(this),
				vf = $f.data( _FIELD_ );

			if ( vf )
			{
				var p = vf.pattern;
				if ( p.slice( 0, 1 ) == '/' )
				{
					p = p.slice( 1 );
				}
				if ( p.slice( -1 ) == '/' )
				{
					p = p.slice( 0, -1 );
				}
				return new RegExp( p ).test( v );
			}
		}
	};

	//	test for borwser support
	$.fn[ _PLUGIN_ ].support = {
		touch: (function()
		{
			return 'ontouchstart' in document.documentElement;
		})(),

		placeholder: (function()
		{
			return 'placeholder' in document.createElement( 'input' );
		})()
	};


	$.fn[ _PLUGIN_ ].debug = function( msg ) {};

	$.fn[ _PLUGIN_ ].deprecated = function( func, alt )
	{
		if ( typeof console != 'undefined' )
		{
			if ( typeof console.error != 'undefined' )
			{
				console.error( func + ' is DEPRECATED, use ' + alt + ' instead.' );
			}
		}
	};


	//	Create debugger is it doesn't already excists
	if ( !$.fn.validValDebug )
	{
		$.fn.validValDebug = function( b )
		{
			$.fn[ _PLUGIN_ ].debug( 'validVal debugger not installed!' );
			return this;
		}
	}


	function isHtmlElement( field )
	{
		if ( typeof HTMLElement != 'undefined' )
		{
			return field instanceof HTMLElement;
		}
		return ( field.nodeType && field.nodeType == Node.ELEMENT_NODE );
	}
	function namespace( events )
	{
		if ( typeof events == 'string' )
		{
			events = events.split( ' ' );
		}
		return events.join( '.vv ' ) + '.vv';
	}
	function unique( arr )
	{
		return $.grep(
			arr,
			function( v, k )
			{
            	return $.inArray( v, arr ) === k;
			}
		);
	}
	function trim( str )
	{
		if ( str === null )
		{
			return '';
		}
		if ( typeof str == 'object' )
		{
			var arr = [];
			for ( var a in str )
			{
				arr[ a ] = trim( str[ a ] );
			}
			return arr;
		}
		if ( typeof str != 'string' )
		{
			return '';
		}
		if ( str.length == 0 )
		{
			return '';
		}

		return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	}
	function stripWhitespace( str )
	{
		if ( str === null )
		{
			return '';
		}
		if ( typeof str == 'object' )
		{
			for ( var a in str )
			{
				str[ a ] = stripWhitespace( str[ a ] );
			}
			return str;
		}
		if ( typeof str != 'string' )
		{
			return '';
		}
		if ( str.length == 0 )
		{
			return '';
		}

		str = trim( str );

		var r = [ ' ', '-', '+', '(', ')', '/', '\\' ];
		for ( var i = 0, l = r.length; i < l; i++ )
		{
			str = str.split( r[ i ] ).join( '' );
		}
		return str;
	}
	function preventkeyup( kc )
	{
		switch( kc ) {
			case 9:		//	tab
			case 13: 	//	enter
			case 16:	//	shift
			case 17:	//	control
			case 18:	//	alt
			case 37:	//	left
			case 38:	//	up
			case 39:	//	right
			case 40:	//	down
			case 224:	//	command
				return true;
				break;

			default:
				return false;
				break;
		}
	}


})( jQuery );