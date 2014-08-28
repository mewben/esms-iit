import DS from 'ember-data';
/*
export default DS.Store.extend({
	adapter: DS.RESTAdapter.extend({
		host: EsmsUiENV.ApiHost.slice(0, -1), // remove / at the end
		namespace: EsmsUiENV.Api	
	})
});
*/
export default DS.RESTAdapter.extend({
	host: EsmsUiENV.ApiHost.slice(0, -1), // remove / at the end
	namespace: EsmsUiENV.Api,
	/*ajax: function(url, method, hash) { // enable CORS for ember-data
		hash = hash || {};
		hash.crossDomain = true;
		//hash.xhrFields = {withCredentials: false};
		return this._super(url, method, hash);
	} */
});