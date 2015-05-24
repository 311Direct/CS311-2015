$(document).ready(function() {
	var effortEst = $('.anchor-effort-est').attr('href');
	var vis = $('.anchor-vis').attr('href');
	$('.anchor-effort-est').attr('href', effortEst + '?id=' + loadContent.itemId());
	$('.anchor-vis').attr('href', vis + '?id=' + loadContent.itemId());
});
