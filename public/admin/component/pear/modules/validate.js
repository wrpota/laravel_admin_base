layui.define(['notice', 'jquery', 'element'], function (exports) {
    "use strict";

    var MOD_NAME = 'validate',
        $ = layui.jquery,
        notice = layui.notice,
        element = layui.element;

    var validate = new function () {
        this.layer = function (res, count = 1) {
            var errors = res.responseJSON.errors;
            if (typeof errors === 'undefined') {
                notice.error(res.responseJSON.msg);
            } else {
                var number = 1;
                $.each(errors, function (i) {
                    notice.error(errors[i][0]);
                    if (number === count) {
                        return false;
                    }
                    ++number;
                });
            }
        },
        this.form = function (res, from) {
            var errors = res.responseJSON.errors;
            if (typeof errors === 'undefined') {
                notice.error(res.responseJSON.msg);
            } else {
                $.each(from, function (i, element) {
                    $.each(errors, function (i,error) {
                        if (element.getAttribute('name') === i) {
                            element.setAttribute('failure', true)
                            $.each(element.parentNode.children, function (i, ele) {
                                if (ele.classList.contains('lay-validate-error')) {
                                    ele.innerHTML = error;
                                    ele.style.display = 'block'
                                }
                            });
                        }
                    });
                });
            }
        },
        this.clear = function (from) {
            var errorList = $('.lay-validate-error');
            $.each(errorList, function (i, ele) {
                ele.style.display = 'none';
            });
            $.each(from, function (i, element) {
                element.removeAttribute('failure');
            });
        }
    };
    exports(MOD_NAME, validate);
})
