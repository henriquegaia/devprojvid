$(document).ready(function () {

    var Register = function () {

        var _idsOptions = '#optDeveloper, #optCompany';

        var _idOptDeveloper = 'optDeveloper';

        var _idOptCompany = 'optCompany';

        var _idCompanyCode = '#companyRegisterCode';

        var _idTypeUser = '#registerTypeUser';

        var _idFieldsNotDeveloper = '#registerFieldsNotDeveloper';

        var _getSelectedValue = function () {

            return $('input:checked').val();

        };

        var _hideCompanyCodeField = function () {

            $(_idCompanyCode).css('display', 'none');

            $(_idCompanyCode).insertAfter(_idFieldsNotDeveloper);

        };

        var _showCompanyCodeField = function () {

            $(_idCompanyCode).insertAfter(_idTypeUser);

            $(_idCompanyCode).css('display', 'block');

        };

        return {
            idsOptions: _idsOptions,
            hideCompanyCodeField: _hideCompanyCodeField,
            hideAndShowFormFields: function () {

                var opt = _getSelectedValue();

                switch (opt) {

                    case _idOptDeveloper:

                        _hideCompanyCodeField();

                        break;

                    case _idOptCompany:

                        _showCompanyCodeField();

                        break;

                    default:

                        break;
                }
            }
        }
    };

    var register = Register();

    register.hideCompanyCodeField();

    $(register.idsOptions).on('click', function () {

        register.hideAndShowFormFields();

    });

});
