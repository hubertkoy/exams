const $ = require('jquery');
require('bootstrap');
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

$(document).ready(function () {
    const showChar = 100;

    $('.more').each(function () {
        const content = $(this).html();
        const text = $(this).text();
        if (text.length > showChar) {
            $(this).addClass('showMore');
            $(this).addClass("less");
            $(this).data('content', content);
            $(this).data('text', text.substr(0, showChar) + '...');
            const html = $(this).data('text');
            $(this).html(html);
        }
    });

    $(".showMore").click(function () {
        if ($(this).hasClass("less")) {
            $(this).html($(this).data('content'));
            $(this).removeClass("less");
        } else {
            $(this).html($(this).data('text'));
            $(this).addClass("less");
        }
    });

    $('.question').on('click', function () {
        $(this).parent().find('.solution').toggleClass('d-none');
    });

    $('#clearSearchQuestion').on('click', function () {
        const searchQuestionInput = $('#searchQuestion');
        searchQuestionInput.val('');
        searchQuestionInput.focus();
        $(this).addClass('d-none');
        $('#questions li').each(function () {
            $(this).removeClass('d-none');
        });
    });

    const searchQuestionInput = $('#searchQuestion');

    searchQuestionInput.on('input', function () {
        const searchQuestionValue = searchQuestionInput.val();
        if (searchQuestionValue.length > 0) {
            $('#clearSearchQuestion').removeClass('d-none');
        } else {
            $('#clearSearchQuestion').addClass('d-none');
        }
        if ($('#datalistSearchQuestion option').filter(function () {
            return this.value === searchQuestionValue;
        }).length) {
            $('.question').each(function () {
                if ($(this).parent().data('question') === searchQuestionValue) {
                    $(this).parent().removeClass('d-none');
                } else {
                    $(this).parent().addClass('d-none');
                }
            });
        } else {
            $('.question').each(function () {
                $(this).parent().removeClass('d-none');
            });
        }
    });
});
