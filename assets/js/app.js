$(document).ready(function() 
{
	$("[name='categories[]']").on('click', function() 
	{
		value = $( this ).val();
		is_check = $( this ).prop( "checked" );

		if(is_check)
		{
			$( "#select-films-"+value ).show();	
		}
		else
		{
			if(value == 1) $(".jouer").prop('checked', false);
			if(value == 2) $(".realiser").prop('checked', false);

			$( "#select-films-"+value ).hide();
		}
	}); 

    $('#submit-add-artiste, #submit-edition-artiste').click(function() 
	{ 
		is_check = $(".categories:checked").length;

		if(!is_check) 
		{
			alert("Veuillez sélectionner une catégorie avant de valider");
			return false;
		}
    });
});