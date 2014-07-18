import Em from 'ember';

export default Em.Handlebars.makeBoundHelper(function(date, format) {
	format = format ? format : 'MMMM DD, YYYY';
	return moment(date).format(format);
});