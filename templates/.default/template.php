<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$this->addExternalJS($this->GetFolder()."/js/Vue.js"); ?>

<div id="catalog-menu" class="catalog-menu">
    <a v-on:click="getSections(<?=$arParams["IBLOCK_ID"]?>, <?=$arParams["COUNT_ITEMS"]?>, $event)" class="catalog-menu__btn js-catalog-btn" href="#">
        <span>&nbsp</span>Каталог
    </a>
    <div class="catalog-menu__list" v-if="showList === 'Y'">
        <div v-if="needLoad === 'Y'" class="catalog-menu__loader">
            <svg width="100" height="100" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#FFD23F" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>
        <ul v-else-if="needLoad === 'N'">
            <li v-for="section in sections">
                <a v-bind:href="section.SECTION_PAGE_URL">
                    <span v-if="section.UF_SVG.length >0" v-html="section.UF_SVG"></span>
                    {{section.NAME}}
                </a>
                <div class='catalog-menu__submenu'>
                    <h3>{{section.NAME}}</h3>
                    <ul>
                        <li v-for="subSection in section.ITEMS">
                            <a v-bind:href="subSection.SECTION_PAGE_URL">{{subSection.NAME}}</a>
                        </li>
                    </ul>
                    <div v-if="section.SHOW_MORE === 'Y'" class="catalog-menu_more"><a v-bind:href="section.SECTION_PAGE_URL">Посмотреть все</a></div>
                </div>
            </li>
        </ul>
        <div v-if="needLoad === 'N'" class="catalog-menu_more"><a href="/catalog/">Посмотреть все</a></div>
    </div>
</div>