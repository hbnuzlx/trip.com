(function($){
	$.fn.spinner = function(opts){
		var defaults = {value:0, min:1, max:99, step:1, isCall:false, sync:true};
		var options = $.extend(defaults, opts);
		var keyCodes = {up:38, down:40};
		var disCode = {space:13, enter:32};
		return this.each(function(){
			var _this = $(this);
			var Dom = (options.isCall) ? true : false;
			var container = $("<div class='spinner'></div>")
				_this.addClass("value").val(options.value).wrap(container)
			var dwonButton = $("<a class='increase' type='button' synclick='"+Dom+"'>+</a>");
			var upButton = $("<a class='decrease' type='button' synclick='"+Dom+"'>-</a>");
			_this.after(dwonButton)
			_this.before(upButton)
			_this.bind('keyup paste change', function (e) {
				_this.val(_this.val().replace(/[^\d]/g,''));
				if (e.keyCode == keyCodes.up) dwonButton.click()
				else if (e.keyCode == keyCodes.down) upButton.click()
			})
			dwonButton.click(function(){
				var newVal = "";
					newVal = parseInt(_this.val()) + options.step;
					_this.val(newVal);
					if (_this.val() >= options.max) {
						_this.addClass("passive"); _this.val(options.max); syncCall(options.sync);
						return ;
					} else {
						_this.removeClass("passive"); syncCall(options.sync);
					}
			})
			upButton.click(function(){
				var newVal = "";
					newVal = parseInt(_this.val()) - options.step;
					_this.val(newVal);
					if (_this.val() <= options.min) {
						_this.addClass("passive"); _this.val(options.min); syncCall(options.sync);
						return ;
					} else {
						_this.removeClass("passive"); syncCall(options.sync);
					}
			})
			syncCall = function(opt){
				if ($.isFunction(opt)) {if (opt.apply() != false) {}} else {}
			}
		})
	}
})(jQuery);