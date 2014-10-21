import Em from 'ember';

export default Em.Handlebars.makeBoundHelper(function(mt, ft) {
	if (! Em.$.isNumeric(mt) || ! Em.$.isNumeric(ft)) {
		return;
	}

	var ave = ((mt - 0) + (ft - 0)) / 2;
	var res = parseFloat(ave.toPrecision(12));

	if (res > 3) {
		return new Em.Handlebars.SafeString( '<span style="color: red; font-weight:bold">' + res.toFixed(1) + '</span>' );
	}
	return res.toFixed(1);
});