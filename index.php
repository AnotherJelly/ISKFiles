<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Подобрать мульти сплит систему #REGIONALITY_REGION_DESCRIPTION#. Интернет магазин ИСК представляет калькулятор подбора мульти сплит-систем.");
$APPLICATION->SetPageProperty("title", "Подбор мульти сплит-системы #REGIONALITY_REGION_DESCRIPTION# - интернет-магазин ИСК");
$APPLICATION->SetTitle("Подбор мульти сплит-системы");
?>
<?php
$hint_result = array(
    "Минимальная допустимая (не рекомендуемая)" => "Загрузка до 150%, существенное снижение производительности и эффективности при одновременной работе всех внутренних блоков",
    "Минимальная рекомендованная" => "Загрузка ≤ 130%, небольшое снижение производительности и эффективности при одновременной работе всех внутренних блоков",
    "Оптимальная рекомендованная"=>"Загрузка ≤ 100%, выполнение ERP");
$hint_counter = 0;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<link rel="stylesheet" type="text/css" href="style_multisplit.css" />
<script src="calc.js"></script>

<div class="intec-content">
    <div class="intec-content-wrapper">

        <div class="calc-form__title">Число комнат</div>
        <!--Radio button-->
        <div class="calc-radio">
            <? for ($i = 2; $i <= 6; $i++) {?>
            <div class="calc-radio__button">
                <input type="radio" id=<?='"'. 'radio-room' . $i . '"'?> name="radio-room" class="radio-room" value=<?='"'. $i . '"'?> <?=  ($i==2) ? "checked" : "" ?>>
                <label for=<?='"'. 'radio-room' . $i . '"'?> class="radio-room__visible"><?= $i ?></label>
            </div>
            <? } ?>
        </div>

        <!--Rooms-->
        <div id="calc-rooms">
            <? for ($i = 1; $i <= 6; $i++) {?>
            <div id="calc-block__room" class="calc-block__room" data-active=<?= ($i<=2) ? "true" : "false" ?>>
                <div class="calc-form__title">Комната <?= $i ?></div>
                <div class="calc-block">
                    <div class="calc-block__form">
                        <label class="calc-form__text">Площадь м<sup>2</sup></label>
                        <input type="number" min="0" max="1000" value="15" maxlength="4" class="room_area intec-ui intec-ui-control-input intec-ui-mod-round-2 intec-ui-view-1">
                    </div>
                    <div class="calc-block__form">
                        <label class="calc-form__text">Высота потолка, м</label>
                        <input type="number" min="1" max="1000" value="2.7" step="0.1" maxlength="4" class="room_height intec-ui intec-ui-control-input intec-ui-mod-round-2 intec-ui-view-1">                
                    </div>
                    <div class="calc-block__form">
                        <div class="calc-form__hint">
                            <label class="calc-form__text">Освещённость солнцем</label>
                            <div class="hint__custom" data-role="hint.custom">
                                <i class="fal fa-question-circle"></i>
                                <div class="hint-content__block" data-active="false">
                                    <div class="hint-content__element">
                                        <div>
                                            <div class="hint-content__title">
                                                Освещённость солнцем
                                            </div>
                                            <span class="popup-window-close-icon popup-window-titlebar-close-icon"></span>
                                        </div>
                                        <div class="hint-content__text">
                                            Для расчета мощности кондиционера важен такой параметр, как удельная мощность — она обозначается как q. Ее значение может варьироваться в зависимости от световых условий, которые преобладают в помещении. 
                                            <ul>
                                            <li>Затенённая комната - освещенность слабая. q = 30 Вт/м³</li>
                                            <li>Солнце попадает в комнату вечером или рано утром - освещенность средняя. q = 35 Вт/м³</li>
                                            <li>Солнечная сторона или окна выходят на юг - освещенность сильная. q = 40 Вт/м³</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <select class="room_insolation intec-ui intec-ui-control-input intec-ui-mod-round-2 intec-ui-view-1" style="appearance: auto;">
                            <option value="30" selected>Слабая (северная сторона)</option>
                            <option value="35">Средняя (запад или восток)</option>
                            <option value="40">Сильная (южная сторона)</option>
                        </select>
                    </div>
                    <div class="calc-block__form">
                        <label class="calc-form__text">Количество людей</label>
                        <input type="number" min="0" max="1000" value="1" maxlength="4" class="room_people intec-ui intec-ui-control-input intec-ui-mod-round-2 intec-ui-view-1">
                    </div>
                </div>
                <div class="calc-block-result calc-form__text">Требуемая производительность блока: <label class="calc-block-result__num">2.00</label> кВт</div>
            </div>
            <? } ?>
        </div>

        <!--Result + hint-->
        <? foreach($hint_result as $hint_name=>$hint_description)
        { ?>
            <div class="calc-form__hint">
                <label class="calc-form__text"><?= $hint_name ?></label>
                <div class="hint__custom" data-role="hint.custom">
                    <i class="fal fa-question-circle"></i>
                    <div class="hint-content__block" data-active="false">
                        <div class="hint-content__element">
                            <div>
                                <div class="hint-content__title">
                                    <?= $hint_name ?>
                                </div>
                                <span class="popup-window-close-icon popup-window-titlebar-close-icon"></span>
                            </div>
                            <div class="hint-content__text">
                                <?= $hint_description ?>
                            </div>
                        </div>
                    </div>
                </div>
                -
                <label id=<?='"'. 'result-' . $hint_counter . '"'?>    ></label>кВт
            </div>
        <? $hint_counter++; 
        } ?>

        <div class="form-result__wrapper">
            <a class="intec-ui intec-ui-control-button intec-ui-size-3 intec-ui-mod-round-3 intec-ui-scheme-current" id="show_models" type="submit">Подобрать внешние блоки</a>
            <div class="hint__custom" data-role="hint.custom">
                <i class="hint__huge">
                    <div>?</div>
                </i>
                <div class="hint-content__block" data-active="false">
                    <div class="hint-content__element">
                        <div>
                            <div class="hint-content__title">
                                Подобрать внешние блоки
                            </div>
                            <span class="popup-window-close-icon popup-window-titlebar-close-icon"></span>
                        </div>
                        <div class="hint-content__text">
							<p>Основной элемент мульти сплит-системы - внешний блок. Внутренние блоки подбираются по совместимости с внешним.</p><p>Выберите внешний блок, менеджеры свяжутся с вами после оформления заказа и подберут 3-4 варианта внутренних. Также выбирайте внутренние блоки самостоятельно на <a href="/catalog/konditsionery/multi_split_sistemy/vnutrennie_bloki_multi_split_system/">сайте</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<br>
        <div>
            <?$APPLICATION->IncludeComponent(
	"intec.universe:main.widget", 
	"form.multisplit", 
	array(
		"COMPONENT_TEMPLATE" => "form.multisplit",
		"WEB_FORM_ID" => "14",
		"SETTINGS_USE" => "N",
		"LAZYLOAD_USE" => "N",
		"CONSENT_SHOW" => "Y",
		"WEB_FORM_TITLE_SHOW" => "Y",
		"WEB_FORM_DESCRIPTION_SHOW" => "Y",
		"WEB_FORM_BACKGROUND" => "theme",
		"WEB_FORM_BACKGROUND_OPACITY" => "",
		"WEB_FORM_TEXT_COLOR" => "light",
		"WEB_FORM_POSITION" => "center",
		"WEB_FORM_ADDITIONAL_PICTURE_SHOW" => "N",
		"BLOCK_BACKGROUND" => "#TEMPLATE_PATH#/images/bg.jpg",
		"BLOCK_BACKGROUND_PARALLAX_USE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONSENT_URL" => "#SITE_DIR#company/consent/",
		"WEB_FORM_CONSENT_SHOW" => "N",
		"WEB_FORM_TITLE_POSITION" => "center",
		"WEB_FORM_DESCRIPTION_POSITION" => "center",
		"WEB_FORM_THEME" => "dark",
		"WEB_FORM_BUTTON_POSITION" => "center",
		"WEB_FORM_BACKGROUND_USE" => "N",
		"FORM_ID" => "14",
		"FORM_TEMPLATE" => ".default",
		"FORM_TITLE" => "",
		"WIDE" => "N",
		"BORDER_STYLE" => "squared",
		"TITLE" => "",
		"DESCRIPTION" => "",
		"BUTTON_TEXT" => "",
		"FORM_TITLE_SHOW" => "Y",
		"FORM_DESCRIPTION_SHOW" => "N",
		"FORM_POSITION" => "center",
		"FORM_ADDITIONAL_PICTURE_SHOW" => "N",
		"FORM_BACKGROUND_PATH" => "",
		"FORM_BACKGROUND_PARALLAX_USE" => "N"
	),
	false
);?>
        </div>
    </div>
</div> 

<div class="form-result-custom__popup" data-active="false">
	<div class="form-result-custom__element" style="padding: 40px;">
    <span class="popup-window-close-icon popup-window-titlebar-close-icon"></span>	
        <div class="intec-grid intec-grid-wrap intec-grid-a-v-center" style="gap: 20px;">
            <div class="intec-grid-item-1 intec-grid intec-grid-a-v-center intec-grid-i-10">
                <div class="form-result-new-message-note-icon intec-grid-item-auto intec-cl-svg">
                    <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M34.937 10.1982L19.5102 25.625L11.7988 17.9116" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M37.875 19.5C37.875 29.6491 29.6491 37.875 19.5 37.875C9.35087 37.875 1.125 29.6491 1.125 19.5C1.125 9.35087 9.35087 1.125 19.5 1.125C22.4665 1.125 25.2595 1.84571 27.7402 3.09317" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>                    
                </div>
                <div class="form-result-new-message-note-text intec-grid-item">
                    Спасибо! Ваша заявка  принята!                    
                </div>
            </div>
            <div class="form-result-new-message-note-buttons intec-grid-item-1" style="display: block;">
                <button class="form-result-new-message-note-button intec-ui intec-ui-control-button intec-ui-scheme-current" data-role="closeButton" style="padding: 10px 20px;">Закрыть</button>                
            </div>
        </div>
	</div>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>