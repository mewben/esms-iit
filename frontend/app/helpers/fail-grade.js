import Em from 'ember';

export default Em.Handlebars.makeBoundHelper(function(gr) {
	if (gr <= 3) {
		return gr;
	} else {
		return new Em.Handlebars.SafeString( '<span style="color: red; font-weight:bold">' + gr + '</span>' );
	}
});