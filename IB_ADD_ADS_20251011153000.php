<?php

namespace Sprint\Migration;


class IB_ADD_ADS_20251011153000 extends Version
{

    protected $description = "Добавляет миграцию для иб Недвижимость";

    public function up() {

        $helper = new HelperManager();

        $arIBlockType = array(
            'ID' => 'ADS',
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => array(
                'ru' => array(
                    'NAME' => 'Объявления',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                ),
                'en' => array(
                    'NAME' => 'Advertisements',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ),
            )
        );


        $helper->Iblock()->addIblockTypeIfNotExists($arIBlockType);

        $iIBlockID = $helper->Iblock()->addIblockIfNotExists(array(
            'LID' => 's1',
            'IBLOCK_TYPE_ID' => 'ADS',
            'CODE' => 'REAL_ESTATE',
            'NAME' => 'Недвижимость'
        ));
        $arProps = array(
            array(
                'NAME' => 'Тип недвижимости',
                'CODE' => 'REAL_ESTATE_TYPE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID' => 'APARTMENT',
                        'VALUE' => 'Квартира'
                    ],
                    [
                        'XML_ID' => 'HOUSE',
                        'VALUE' => 'Дом'
                    ],
                    [
                        'XML_ID' => 'ROOM',
                        'VALUE' => 'Комната'
                    ],
                    [
                        'XML_ID' => 'LAND_PARCEL',
                        'VALUE' => 'Участок'
                    ],
                    [
                        'XML_ID' => 'COMMERCIAL',
                        'VALUE' => 'Коммерческая'
                    ],
                ]
            ),
            array(
                'NAME' => 'Тип сделки',
                'CODE' => 'DEAL_TYPE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID' => 'SALE',
                        'VALUE' => 'Продажа'
                    ],
                    [
                        'XML_ID' => 'RENT',
                        'VALUE' => 'Аренда'
                    ],
                    [
                        'XML_ID' => 'DAILY',
                        'VALUE' => 'Посуточно'
                    ],
                ]
            ),
            array(
                'NAME' => 'Город',
                'CODE' => 'CITY',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID' => 'MOSCOW',
                        'VALUE' => 'Москва'
                    ],
                    [
                        'XML_ID' => 'ST_PETERSBURG',
                        'VALUE' => 'Санкт-Петербург'
                    ],
                    [
                        'XML_ID' => 'NOVOSIBIRSK',
                        'VALUE' => 'Новосибирск'
                    ],
                    [
                        'XML_ID' => 'KRASNODAR',
                        'VALUE' => 'Краснодар'
                    ],
                    [
                        'XML_ID' => 'YEKATERINBURG',
                        'VALUE' => 'Екатеринбург'
                    ],
                    [
                        'XML_ID' => 'OTHER_REGIONS',
                        'VALUE' => 'Другие регионы'
                    ],
                ]
            ),
            array(
                'NAME' => 'Адрес',
                'CODE' => 'ADDRESS',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Площадь',
                'CODE' => 'AREA',
                'PROPERTY_TYPE' => 'N'
            ),
            array(
                'NAME' => 'Количество комнат',
                'CODE' => 'ROOMS',
                'PROPERTY_TYPE' => 'N'
            ),
            array(
                'NAME' => 'Фото',
                'CODE' => 'PHOTOS',
                'PROPERTY_TYPE' => 'F',
                'MULTIPLE' => 'Y'
            ),
            array(
                'NAME' => 'Этаж',
                'CODE' => 'FLOOR',
                'PROPERTY_TYPE' => 'N'
            ),
            array(
                'NAME' => 'Этажность дома',
                'CODE' => 'FLOORS_TOTAL',
                'PROPERTY_TYPE' => 'N'
            ),
            array(
                'NAME' => 'Цена',
                'CODE' => 'PRICE',
                'PROPERTY_TYPE' => 'N'
            ),
            array(
                'NAME' => 'Контактное лицо',
                'CODE' => 'CONTACT_PERSON',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Телефон',
                'CODE' => 'PHONE',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Email',
                'CODE' => 'EMAIL',
                'PROPERTY_TYPE' => 'S'
            )
        );

        if ($iIBlockID) {
            foreach ($arProps as $arProp) {
                $helper->Iblock()->addPropertyIfNotExists($iIBlockID, $arProp);
            }
        }

    }

    public function down() {
        $helper = new HelperManager();

        $helper->Iblock()->deleteIblockIfExists('REAL_ESTATE', 'ADS');

    }

}
