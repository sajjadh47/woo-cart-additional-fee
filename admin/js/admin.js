jQuery( document ).ready( function( $ )
{
	$( '#wcfee_product_filter' ).select2(
	{
		placeholder: Woo_Cart_Additional_Fee.wcfee_product_filter_txt_i18n
	} );
	
	$( '#wcfee_country_filter' ).select2(
	{
		placeholder: Woo_Cart_Additional_Fee.wcfee_country_filter_txt_i18n
	} );

	$( '#wcfee_type' ).select2(
	{
		placeholder: Woo_Cart_Additional_Fee.wcfee_type_txt_i18n
	} );

	$( "#wcfee_fixed" ).closest( 'tr' ).hide();
	
	$( "#wcfee_percentage" ).closest( 'tr' ).hide();

	$( '#wcfee_type' ).change( function( event )
	{	
		if ( $( this ).val() == 'fixed' )
		{
			$( "#wcfee_percentage" ).closest( 'tr' ).hide();
		
			$( "#wcfee_fixed" ).closest( 'tr' ).show();
		}
		else if( $( this ).val() == 'percentage' )
		{
			$( "#wcfee_fixed" ).closest( 'tr' ).hide();
		
			$( "#wcfee_percentage" ).closest( 'tr' ).show();
		}
	} );

	$( '#wcfee_enable_minimum' ).click( function( event )
	{	
		$( this ).is( ':checked' ) ? $( "#wcfee_minimum" ).closest( 'tr' ).show() : $( "#wcfee_minimum" ).closest( 'tr' ).hide();
	} );

	$( '#wcfee_enable_maximum' ).click( function( event )
	{	
		$( this ).is( ':checked' ) ? $( "#wcfee_maximum" ).closest( 'tr' ).show() : $( "#wcfee_maximum" ).closest( 'tr' ).hide();
	} );

	if ( $( "#wcfee_minimum" ).is( ':checked' ) )
	{
		$( "#wcfee_minimum" ).closest( 'tr' ).show();
	}
	else
	{
		$( "#wcfee_minimum" ).closest( 'tr' ).hide()
	}

	if ( $( "#wcfee_maximum" ).is( ':checked' ) )
	{
		$( "#wcfee_maximum" ).closest( 'tr' ).show();
	}
	else
	{
		$( "#wcfee_maximum" ).closest( 'tr' ).hide();
	}
	
	if ( $( '#wcfee_type' ).val() == 'fixed' )
	{	
		$( "#wcfee_fixed" ).closest( 'tr' ).show();
	}
	else if( $( '#wcfee_type' ).val() == 'percentage' )
	{	
		$( "#wcfee_percentage" ).closest( 'tr' ).show();
	}

	$( '#wcfee_product_filter' ).parent().append( `<br /><a class="select_all button" href="#">${Woo_Cart_Additional_Fee.wcfee_select_all_txt_i18n}</a> <a class="select_none button" href="#">${Woo_Cart_Additional_Fee.wcfee_select_none_txt_i18n}</a>` );
} );