import Base from './base';
export default Base.extend({

	// for print preview
	params: function() {
		var p = this._params();
		p.print = true;
		return p;
	}.property('sy', 'sem'),

	actions: {
		retrieve: function() {
			var param = {
				sy: this.get('sy'),
				sem: this.get('sem')
			};
			this.send('search', '/reports/sumbilling?', param);
		}
	},

	_params: function() {
		return {
			sy: this.get('sy'),
			sem: this.get('sem')
		};
	}
});