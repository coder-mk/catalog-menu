$(function(){
    var menuSectionsModel = {
        showList: "N",
        sections: "",
        needLoad: 'Y'
    };

    var menuSections = new Vue({
        el: '#catalog-menu',
        data: menuSectionsModel,
        methods: {
            // Показать меню каталога
            getSections: function (iblock_id, cnt, event) {

                menuSectionsModel.showList = "Y";

                if(menuSectionsModel.needLoad=="Y"){
                    BX.ajax.runComponentAction('site:menu.catalog', 'getSections', {
                        mode: 'class',
                        data: {post: {
                                iblock_id: iblock_id,
                                cnt: cnt,
                            }}
                    })
                        .then(function(response) {
                            if (response.status === 'success') {
                                console.log(response);
                                // console.log(response.data.ITEMS.UF_SVG.replace(/^.|.$/g,""));
                                menuSectionsModel.sections = response.data.ITEMS;
                                menuSectionsModel.needLoad = "N";
                            }else{
                                console.log(response);
                            }
                        }, function (response) {
                            if(response.status == 'error'){
                                console.log(response.errors[0].message);
                            }
                        });
                }

                $(event.target).next(".catalog-menu__list").slideToggle();
                event.preventDefault();
            },
        },
        created() {
            // Позиционарование списка с разделами каталога по нижней части блока
            $(".catalog-menu__list").css("top", $(".js-modal-wrap").outerHeight());
        }
    });

    // Закрыть меню каталога при клике за его пределами
    $(document).mouseup( function(e){
        if($(menuSections.$el).has(e.target).length===0 && $(menuSections.$el).find(".catalog-menu__list").is(':visible')) {
            $(menuSections.$el).find(".catalog-menu__list").slideToggle();
        }
    });

})
