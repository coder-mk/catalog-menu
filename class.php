<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Data\Cache;

class StoresList extends CBitrixComponent implements Controllerable
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "COUNT_ITEMS" => intval($arParams["COUNT_ITEMS"]),
        );
        return $result;
    }

    public function executeComponent()
    {
        if (!\Bitrix\Main\Loader::includeModule("catalog")) {
            $this->abortResultCache();
            ShowError(GetMessage("CATALOG_MODULE_NOT_INSTALL"));
            return;
        }

        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        return [
            'getSections' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(array(ActionFilter\HttpMethod::METHOD_POST))
                ],
            ],
        ];
    }
    public function getSectionsAction($post)
    {
        $data = [];

        // Получение разделов первого уровня
        $arFilter = array('IBLOCK_ID' => $post["iblock_id"], 'ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1);
        $rsSections = CIBlockSection::GetList(array('sort' => 'ASC'), $arFilter, false, array("NAME", "SECTION_PAGE_URL", "UF_IMG", "UF_SVG"), Array("nPageSize"=>$post["cnt"]));
        while ($arSection = $rsSections->GetNext())
        {
            $arSection["UF_SVG"] = htmlspecialchars_decode($arSection["UF_SVG"]);
            $arSection["IMG"] = CFile::GetPath($arSection["UF_IMG"]);
            $data["ITEMS"][] = $arSection;
        }

        // Получение подразделов для разделов первого уровня
        foreach ($data["ITEMS"] as &$section) {
            $arFilter = array('IBLOCK_ID' => $post['iblock_id'], 'ACTIVE' => 'Y', 'SECTION_ID'=>$section['ID'],  'DEPTH_LEVEL' => 2);
            $rsSections = CIBlockSection::GetList(array('sort' => 'ASC'), $arFilter, false, array("NAME", "SECTION_PAGE_URL"), Array("nPageSize"=>$post["cnt"]-2));
            while ($arSection = $rsSections->GetNext())
            {
                $section["ITEMS"][] = $arSection;
            }
            $section["SHOW_MORE"] = (count($section["ITEMS"])==$post["cnt"]-2) ? "Y" : "N" ;
        }

        return ($data);
    }
}?>
