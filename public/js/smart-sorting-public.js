let first = 0;
let products = new Map();
let plugin_path = window.location.protocol + "//" + window.location.host + "/wp-content/plugins/SmartSorting-Plugin/public";

(function($){
	let current = get_first();
	$(document).scroll(function() {
		// проверяем
		//console.clear();
		current = get_first();
		if(first !== current) {
			first = current;
			products = get_products();
		}
		products.forEach((value, key) => checkPosition(key, $));
	});

	// после загрузки страницы сразу проверяем
	//console.clear();
	first = current;
	products = get_products();
	products.forEach((value, key) => checkPosition(key, $));

	// проверка при ресайзе страницы
	$(window).resize(function(){
		//console.clear();
		current = get_first();
		if(first !== current) {
			first = current;
			products = get_products();
		}
		products.forEach((value, key) => checkPosition(key, $));
	});

})( jQuery );

/** функция проверки полной видимости элемента
 *
 * @param $
 * @param {number} element
 */
function checkPosition(element, $){
	let elementClass = '.post-' + element;

	// координаты дива
	let div_position = $(elementClass).offset();
	// отступ сверху
	let div_top = div_position.top;
	// отступ слева
	let div_left = div_position.left;
	// ширина
	let div_width = $(elementClass).width();
	// высота
	let div_height = $(elementClass).height();

	// проскроллено сверху
	let top_scroll = $(document).scrollTop();
	// проскроллено слева
	let left_scroll = $(document).scrollLeft();
	// ширина видимой страницы
	let screen_width = $(window).width();
	// высота видимой страницы
	let screen_height = $(window).height();

	// координаты углов видимой области
	let see_x1 = left_scroll;
	let see_x2 = screen_width + left_scroll;
	let see_y1 = top_scroll;
	let see_y2 = screen_height + top_scroll;

	// координаты углов искомого элемента
	let div_x1 = div_left;
	let div_x2 = div_left + div_height;
	let div_y1 = div_top;
	let div_y2 = div_top + div_width;

	// проверка - виден див полностью или нет
	if (div_x1 >= see_x1 && div_x2 <= see_x2 && div_y1 >= see_y1 && div_y2 <= see_y2) {
		if (!products.get(element)) {
			products.set(element, true);
			$.ajax({
				type: "POST",
				url: plugin_path + "/php/class-smart-sorting-track-views.php",
				data: { product_id: element },
			});
		}
	}
}

function get_first(){
	let card = document.querySelector('.product');
	if (card == null) return null;
	let num = card.className.indexOf('post-') + 5;
	num = parseInt(card.className.substring(num, card.className.indexOf(' ', num)));
	return num;
}

function get_products(){
	let elements = new Map();
	let cards = document.querySelectorAll('.product');
	cards.forEach(card => {
		let num = card.className.indexOf('post-') + 5;
		num = parseInt(card.className.substring(num, card.className.indexOf(' ', num)));
		elements.set(num, false);
	})
	return elements;
}