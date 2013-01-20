var icecast_onair__update = function() {
	jQuery.ajax({
		url: ls_icecast_onair_url,
		cache: false,
		timeout: 10000,
		success: function(data) {
			jQuery(".icecast_onair_live").html(data);
		}
	});
	setTimeout(function() { icecast_onair__update(); }, 15000);
}
setTimeout(function() { icecast_onair__update(); }, 5000);