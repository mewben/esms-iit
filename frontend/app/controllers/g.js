import Em from 'ember';

export default Em.Controller.extend({
	version: EsmsUiENV.version,
	sem: {
		sy: window.sem.sy,
		sem: window.sem.sem
	},
	currentDate: window.currentDate,
	pclass: 'screen', 	// page class
	isProc: false, 			// isProcessing default false
	listLim: 10,		// list limit

	msg: {
		sem_changed: 'Semester Changed.',
		del_confirm: 'Are you sure you want to delete?'
	},

	// global service
	getJSON: function(url, param) {
		var dfd = Em.$.Deferred();
		var self = this;

		if (!param)	{
			param = {};
		}
		
		this.set('isProc', true);
		Em.$.getJSON(EsmsUiENV.ApiHost + EsmsUiENV.Api + url + Em.$.param(param))
			.done(function(res) {
				dfd.resolve(res);
			})
			.fail(function(err) {
				dfd.reject(err);
			})
			.always(function() {
				self.set('isProc', false);
			});

		return dfd.promise();
	},

	post: function(url, param) {
		var dfd = Em.$.Deferred();
		var self = this;

		this.set('isProc', true);
		Em.$.post(EsmsUiENV.ApiHost + EsmsUiENV.Api + url, param)
			.done(function(res) {
				dfd.resolve(res);
			})
			.fail(function(err) {
				dfd.reject(err);
			})
			.always(function() {
				self.set('isProc', false);
			});
		return dfd.promise();
	}
});