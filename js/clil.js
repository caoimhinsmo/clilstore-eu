$('#dmenu').hover(function(){
	  $('#navbarDropdown').trigger('click')
            //console.log('hover');
})

$('ul.nav li.active').hover(function(){
    $('div.collapse').toggleClass(' show')
});