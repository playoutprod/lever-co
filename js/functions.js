jQuery(document).ready(function( $ ) {

	$('.lever_co .filters.search .et_pb_button').each(function(){
		$(this).click(function(e){
			e.preventDefault();
			var filters_div = $(this).parent().parent();
			window.location.href = $(this).attr('href')+get_url(filters_div);
		})
	});
	$('.lever_co select').change(function(){
		var filters_div = $(this).parent().parent().parent().parent();
		if(!filters_div.hasClass('search')){
			window.location.href = get_url(filters_div);
		}
  })
});

function get_url(div){
	filters = new Array();
	$(div).find('select').each(function(){
		if($(this).val() != ''){
			filter = {
				"id" : $(this).attr('data-original_id'),
				"value" : $(this).val()
			};
			filters.push(filter);
		}
	});
	var url = '';
	if(!div.hasClass('search')){
		 url += window.location.href.split('?')[0];
	}
	if(filters.length>0){
		url += "?";
	}
	$(filters).each(function(){
		url+=this.id+'='+this.value+"&"
	});
	return(url);
}
