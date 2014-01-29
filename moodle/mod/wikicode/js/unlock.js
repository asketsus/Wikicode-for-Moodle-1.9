$('.unlock').click(function () {
	var $pageid = substr(document.URL,strrpos(document.URL, "=")+1); 
	var $b = $("input[type=checkbox]");
		
	$b.each(function (index) {
		if ( $(this).is(":checked") ) {
			
			$code = $(this).val();
			
			$.getJSON("editorlib.php", { codigo: $code, pageid: $pageid, mode: "3" }, function(json) {
        		location.reload();
       		}
    		);
		}
	})
});

$("input#id_btnunlock").click(function() {
	var $pageid = substr(document.URL,strrpos(document.URL, "=")+1);
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
	window.open("unlock.php?pageid=" + $pageid,"",opciones);
});

$("input#id_btnref").click(function() {
	location.reload();
});

$('.cancelUnlock').click(function() {
	window.close();
});
