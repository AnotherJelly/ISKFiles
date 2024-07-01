let roomsQuantity = $('input.radio-room[checked]').attr('value');
function sumAll () {
    let allActive = $('.calc-block__room[data-active="true"] .calc-block-result__num');
    let allSum = 0;

    allActive.each(function() {
        allSum += Number($(this).val());
    });

    let minRec = (allSum * (1-0.23)).toFixed(2);
    let optRec = allSum.toFixed(2);
	document.getElementById('result-0').innerHTML = (allSum * (1-0.33)).toFixed(2);
	document.getElementById('result-1').innerHTML = minRec;
	document.getElementById('result-2').innerHTML = optRec;

    $('#show_models').attr('href', '/catalog/konditsionery/multi_split_sistemy/vneshnie_bloki_multi_split_system/filter/property_quantity_of_strips-from-' + minRec + '-to-' + Math.round(optRec*1.33) + '/property_quantity_block-is-' + roomsQuantity + '/apply/');
}
function resultPower(element) {
    const parent = $(element).closest('.calc-block__room');
    const s = Number($(parent).find('input.room_area').val());
    const h = Number($(parent).find('input.room_height').val());
    const g = Number($(parent).find('select.room_insolation').val());
    const people = Number($(parent).find('input.room_people').val());
    temp = s*h*g/1000 + people/10;

	res = temp.toFixed(2) > 2 ? temp.toFixed(2) : "2.00";

    $(parent).find('label.calc-block-result__num').html(res);
    $(parent).find('label.calc-block-result__num').val(res);

    sumAll();
}
function radioButton(element) {
    let allRooms = $('.calc-block__room');
    let i=0;
    roomsQuantity = element.value;
    allRooms.each(function() {
        i < element.value ? this.setAttribute("data-active", "true") : this.setAttribute("data-active", "false");
        i++;
    });
    sumAll();
}

$(document).ready(function() {
    let allRes = $('.calc-block-result__num');
    allRes.each(function() {
        $(this).val(2);
    });
    resultPower();
    
    $('#calc-rooms input, #calc-rooms select').on("change", function() {
        resultPower(this);
    });
    $('input.radio-room').on("click", function() {
        radioButton(this);
    });
    $('div[data-role="hint.custom"]').on("click", function() {
        let content = $(this).find('div.hint-content__block');
        if (content.attr('data-active') == "true") {
            content.attr("data-active", "false");
            $('body').removeClass('overflow-hidden'); 
        }
        else {
            content.attr("data-active", "true");
            $('body').addClass('overflow-hidden');
        }
    });

    $('div#calc-rooms').on('input', 'input[type="number"][maxlength]', function(){
        if (this.value.length > this.maxLength){
            this.value = this.value.slice(0, this.maxLength);
        }
    });

    $('textarea[name="form_textarea_58"]').attr("placeholder", "В этом поле вы можете указать особенности помещения или ваши пожелания. Например, требуются модели с минимальным энергопотреблением…");
	$('input[name="form_text_55"]').mask('+7 (000) 000-00-00');
    $('input[name="form_text_55"]').attr("placeholder", '+7 (000) 000-00-00');
});