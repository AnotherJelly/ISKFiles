<?php if (defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true) ?>
<?php
//Функция для извлечения числа из строки
function extractNumber($str) {
    preg_match('/^[0-9]+(?:\.[0-9]+)?/', $str, $matches);
    return isset($matches[0]) ? (float)$matches[0] : null;
}
//Функция сравнения
function customCompare($a, $b) {
    $numA = extractNumber($a);
    $numB = extractNumber($b);
    if ($numA !== null && $numB !== null) {
        return $numA <=> $numB;
    }
    if ($numA !== null) {
        return -1;
    }
    if ($numB !== null) {
        return 1;
    }
    return strcasecmp($a, $b);
}

foreach ($arResult['PROPERTIES']['FILTER_PROPERTY']['VALUE'] as $arPropertyFilter) {
    $new_prop = true;
    foreach ($arResult['PROPERTIES']['PRODUCTS']['VALUE'] as $arItemFilter) {
        $PropertyFilterAll = CIBlockElement::GetProperty("16", $arItemFilter, array("sort" => "asc"), Array("CODE" => $arPropertyFilter));  
        while ($PropertyFilter = $PropertyFilterAll->GetNext()) {
            $PropertyFilterValue = ($PropertyFilter['VALUE_ENUM'] == null) ? $PropertyFilter['VALUE'] : $PropertyFilter['VALUE_ENUM'];
            $PropertyFilterValue = str_replace(',', '.', $PropertyFilterValue);
            $PropertyFilterValue = preg_replace('/(\d+)\.(\d*?)0+\b/', '$1.$2', $PropertyFilterValue);
            $PropertyFilterValue = preg_replace('/(\d+)\. /', '$1 ', $PropertyFilterValue);
            $PropertyFilterValue = rtrim($PropertyFilterValue, '.');
            if ($new_prop) {
                $arTestProperties[$PropertyFilter['CODE']]['NAME'] = $PropertyFilter['NAME'];
                $arTestProperties[$PropertyFilter['CODE']]['HINT'] = $PropertyFilter['HINT'];
                $arTestProperties[$PropertyFilter['CODE']]['PROPERTY_TYPE'] = $PropertyFilter['PROPERTY_TYPE'];
                $curPropertyCode = $PropertyFilter['CODE'];
                $new_prop = false;
            }
            if (!in_array($PropertyFilterValue, $arTestProperties[$PropertyFilter['CODE']]['VALUE'])) {
                $arTestProperties[$PropertyFilter['CODE']]['VALUE'][] = $PropertyFilterValue;
            }
        }   
    }   
    usort($arTestProperties[$curPropertyCode]['VALUE'], 'customCompare');
}
?>
<div>
    <div class="custom-filter">
        <?foreach ($arTestProperties as $propKey => $propElement) {
            if ($propElement['VALUE'] != null) {?>
                <div data-item="filter.list" style="position: relative;">
                    <div class="filter-block-button" data-name="<?= $propElement['NAME'] ?>">
                        <?= $propElement['NAME'] ?>
                    </div>
                    <div class="filter-block-value">
                        <? switch ($propElement['PROPERTY_TYPE']) {
                            case "E":
                                foreach ($propElement['VALUE'] as $propElementValue) {
                                    if ($propElementValue != "") {
                                        $propElementName = CIBlockElement::GetByID($propElementValue)->GetNext();?>
                                        <div>
                                            <input type="checkbox" data-filter="checkbox" data-value="<?= $propElementName['NAME'] ?>" name="<?= $propKey ?>" id="<?= $propKey . ": " . $propElementValue ?>">
                                            <label for="<?= $propKey . ": " . $propElementValue ?>" class=""><?= $propElementName['NAME'] ?></label>
                                        </div>
                                    <?}
                                }
                                break;
                            default:
                                foreach ($propElement['VALUE'] as $propElementValue) {
                                    if ($propElementValue != "") {?>
                                        <div>
                                            <input type="checkbox" data-filter="checkbox" data-value="<?= $propElementValue ?>" name="<?= $propKey ?>" id="<?= $propKey . ": " . $propElementValue ?>">
                                            <label for="<?= $propKey . ": " . $propElementValue ?>" class=""><?= $propElementValue ?></label>
                                        </div>
                                    <?}
                                }
                                break;
                        }
                        ?>
                    </div>
                </div>
            <?}
        }?>
    </div>
    <input type="button" name="" id="button-filter-reset" value="сбросить">
</div>
<script>
    $(document).ready(function() {
        $('.filter-block-button').on('click', function () {
            parent_block = $(this).parent('[data-item="filter.list"]');
            block_value = $('.filter-block-value', parent_block);
            block_value.css('display') == "block" ? block_value.css('display', 'none') : block_value.css('display', 'block');
        });

		document.querySelectorAll('.filter-block-value').forEach(element => {
			const windowWidth = window.innerWidth;
			element.classList.add("block");
			const elementRect = element.getBoundingClientRect();
			const elementHidesLeft = elementRect.left < 0;
			const elementHidesRight = elementRect.right > windowWidth;
			element.classList.remove("block");
			if (elementHidesLeft) {
				element.style.transform = "none";
			} else if (elementHidesRight) {
				element.style.transform = "translateX(-" + element.parentNode.clientWidth + "px)";
			}
		});
    });
    template.load(function (data) {
        var $ = this.getLibrary('$');

        var items = [];
        $('[data-filter="true"]').each(function () {
            var item = {
                'item': $(this),
                'value': $(this).attr('data-filter-data')
            };
            items.push(item);
        });
        function brandSearch(filterArray) {
            $(items).each(function () {
                if (!!this.value) {
                    let allProp = 0;
                    let curProp = 0;
                    for (let element in filterArray) {
                        for (property in filterArray[element]) {
                            if(this.value.includes(filterArray[element][property])) {
                                curProp++;
                            }
                        }
                        allProp++;
                    }
                    if (allProp <= curProp) {
                        this.item.attr('data-active', 'true');
                    } else {
                        this.item.attr('data-active', 'false');
                    }
                }
            });
        }
        $('[data-filter="checkbox"]').on('click', function () {
            let checked = $('[data-filter="checkbox"]:checked');
            let filterArray = new Array();
            let counter = 0;
            document.querySelectorAll('.filter-block-button').forEach((element) => element.innerHTML = element.dataset.name);
            checked.each(function() {
                filterArray[$(this).attr('name')] = {};
                block_element = $(this).closest('[data-item="filter.list"]').find('.filter-block-button');
                block_text = block_element.html();
                block_element.html(block_text + " " + $(this).attr('data-value'));
            });
            checked.each(function() {
                filterArray[$(this).attr('name')][counter] = $(this).attr('id');
                counter++;
            });
            brandSearch(filterArray);

            let propActiveEl = "";
            $('[data-filter="true"][data-active="true"]').each(function () {
                propActiveEl += $(this).attr('data-filter-data');
            });
            checkboxSelector = '[data-filter="checkbox"]:not([name="' + $(this).attr('name') + '"])';
            $(checkboxSelector).each(function() {
                if (propActiveEl.includes($(this).attr('id'))) {
                    $(this).attr('data-active', 'true');
                }
                else {
                    $(this).attr('data-active', 'false');
                    $(this).prop('checked', false);
                }
            });
        });
        $('#button-filter-reset').on('click', function () {
            document.querySelectorAll('[data-filter="checkbox"]:checked').forEach((element) => element.checked = false);
            document.querySelectorAll('[data-filter="checkbox"]').forEach((element) => element.dataset.active = "true");
            document.querySelectorAll('.filter-block-button').forEach((element) => element.innerHTML = element.dataset.name);
            $(items).each(function () {
                this.item.attr('data-active', 'true');
            });
        });
    });
</script>