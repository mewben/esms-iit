import Em from 'ember';

export default Em.Handlebars.makeBoundHelper(function(value) {
	return accounting.formatMoney(value, '');
});