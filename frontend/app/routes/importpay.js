import Ember from 'ember';

export default Ember.Route.extend({
	actions: {
		load2: function(what) {
			var self = this;
			this.store.find(what).then(function(h) {
				var bcodes = [
					{v: 'FCB', name: 'FCB'},
					{v: 'ALAY', name: 'Alay-lakad Inc.'},
					{v: 'LGUTAG', name: 'LGU-Tagbilaran'},
					{v: 'DOST', name: 'DOST'},
					{v: 'CPG', name: 'CPG'}
				];
				/*var bcodes = [
					{bcode: 'test1', desc: 'hello'},
					{bcode: 'test12', desc: 'hello2'}
				];*/
				console.log(bcodes);
				console.log(h);
				self.controllerFor('importpay').set('bcodes', h['bcodes']);
			});

			//this.controllerFor('importpay').set('bcodes', [{'v': 'test', 'name': 'hello'}, {'v': 'lskjdf', 'name': 'testing'}]);
			//this.controllerFor('importpay').set('bcodes', this.store.find(what));
		}
	}
});