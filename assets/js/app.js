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

	$('input[js=radiosearch]').change(function()
	{
		baseUrl = $(".header__logo").attr("href");

		value = $(this).val();
		$("#search").attr('action',''+baseUrl+'/'+value+'');
	});


	$('#submit-comment').click(function(Event) 
    { 
		let rating = $("#rating").val();
	
        if(!rating) 
        {
            // rating = $(".rating-star-7").css("background-color", "red");
            alert("Veuillez sélectionner une note à l'aide des étoiles avant de valider");
            Event.preventDefault();
            return false;
        }
    });

	/*******************************************************************/
	/****** J'aime/J'aime pas un commentaire *******************git ********/
	/*******************************************************************/

	$(document).on('click', '.voteCom' , function() 
	{
		let commentaire = $(this).attr('com');
		let user =  $(this).attr('user');
		let vote = $(this).attr('vote');
		let url = $('#url').attr('url');

		chemin = ''+url+'/vote/'+commentaire+'/'+user+'/'+vote+'';

		$.ajax(
		{
			url: ''+chemin+'',
			ifModified:true,
			success: function(retour)
			{
				variable = JSON.parse(retour);
				let nbnegatif = variable[0];
				let nbpositif = variable[1];

				$('#com-'+commentaire+'-positif').html(nbpositif);
				$('#com-'+commentaire+'-negatif').html(nbnegatif);
			}

		});
	});
});