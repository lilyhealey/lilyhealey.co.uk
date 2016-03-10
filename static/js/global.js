// JavaScript Document

/*$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});*/

/*var int=self.setInterval(function(){displaydate()},1000);

$('#londondate').ready(function()
{
	displaydate()
	{
		var d = new Date();
		var utc = d.getTime() + (d.getTimezoneOffset() * 60000);
		var du = new Date(utc);
		var localarr = new Array(d.getDate().toString(), d.getMonth().toString(), d.getFullYear().toString(), d.toLocaleTimeString());
		var londonarr = new Array(du.getDate().toString(), du.getMonth().toString(), du.getFullYear().toString(), du.toLocaleTimeString());
		var montharr = new Array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
		//var london = document.getElementById("londondate");
		var london = document.getElementById("londondate")
		london.innerHTML =  localarr[0] + " " + montharr[localarr[1].valueOf()] + " " + localarr[2] + " " + localarr[3];
		//london.innerHTML = londonarr[0] + " " + montharr[londonarr[1].valueOf()-1] + " " + londonarr[2] + " " + londonarr[3];
		//x.innerHTML=d.toUTCString();
	};
});*/

$(document).ready(function(){
				$(".fancybox")
					.attr('rel', 'gallery')
					.fancybox({
						padding: 0,
						openEffect	: 'none',
						closeEffect	: 'none',
						prevEffect : 'none',
						nextEffect : 'none'
					});
				});