<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!$USER->IsAdmin()) {
    LocalRedirect('/');
}
\Bitrix\Main\Loader::includeModule('iblock');
$row = 1;
$IBLOCK_ID = 17;

$el = new CIBlockElement;
$arProps = [];

$rsProp = CIBlockPropertyEnum::GetList(
    ["SORT" => "ASC", "VALUE" => "ASC"],
    ['IBLOCK_ID' => $IBLOCK_ID]
);
while ($arProp = $rsProp->Fetch()) {
    $key = trim($arProp['VALUE']);
    $arProps[$arProp['PROPERTY_CODE']][$key] = $arProp['ID'];
}

$rsElements = CIBlockElement::GetList([], ['IBLOCK_ID' => $IBLOCK_ID], false, false, ['ID']);
while ($element = $rsElements->GetNext()) {
    CIBlockElement::Delete($element['ID']);
}

if (($handle = fopen("realestate.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if ($row == 1) {
            $row++;
            continue;
        }
        $row++;

        $PROP['REAL_ESTATE_TYPE'] = $data[7];
        $PROP['DEAL_TYPE'] = $data[8];
        $PROP['CITY'] = $data[9];
        $PROP['ADDRESS'] = $data[10];
        $PROP['AREA'] = $data[11];
        $PROP['ROOMS'] = $data[12];
        $PROP['FLOOR'] = $data[13];
        $PROP['FLOORS_TOTAL'] = $data[14];
        $PROP['PRICE'] = str_replace([' ', ','], '', $data[3]);
        $PROP['DATE'] = date('d.m.Y');
        $PROP['CONTACT_PERSON'] = $data[4];
        $PROP['PHONE'] = $data[5];
        $PROP['EMAIL'] = $data[6];

        foreach ($PROP as $key => &$value) {
            $value = trim($value);
            $value = str_replace('\n', '', $value);

            if ($key == 'CITY') {
                if (isset($arProps['CITY'][$value])) {
                    $value = $arProps['CITY'][$value];
                } else {
                    $value = $arProps['CITY']['Другие регионы'];
                }
            } elseif (isset($arProps[$key][$value])) {
                $value = $arProps[$key][$value];
            }
        }

        $numberFields = ['FLOOR', 'FLOORS_TOTAL', 'ROOMS'];
        foreach ($numberFields as $field) {
            if (!isset($PROP[$field]) || !is_numeric($PROP[$field])) {
                $PROP[$field] = null;
            }
        }

        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $data[1],
            "PREVIEW_TEXT" => $data[2],
            "DETAIL_TEXT" => $data[2],
            "ACTIVE" => end($data) ? 'Y' : 'N',
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
    }
    fclose($handle);
}
