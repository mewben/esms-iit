import Spin from './submit-spin';

export default Spin.extend({
	icon: 'fa-send',
	btnClass: 'btn-sm btn-info',
	actions: {
		click: function() {
			this.sendAction();
		}		
	}
});