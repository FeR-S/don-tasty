jQuery(document).ready(function ($) {

    $(".headroom").headroom({
        "tolerance": 20,
        "offset": 50,
        "classes": {
            "initial": "animated",
            "pinned": "slideDown",
            "unpinned": "slideUp"
        }
    });

    // Закрываем элемент по клику вне его области
    $(document).mouseup(function (e) { // событие клика по веб-документу
        var current_block = $(".search-result"); // тут указываем ID элемента
        if (!current_block.is(e.target) // если клик был не по нашему блоку
            && current_block.has(e.target).length === 0) { // и не по его дочерним элементам
            current_block.hide(); // скрываем его
        }
    });


    $(document).on('beforeSubmit', '#article_search_form', function () {
        return submitForm($(this));
    });

    function submitForm(form) {

        console.log(form.data("yiiActiveForm").validated);

    }

});