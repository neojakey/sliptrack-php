function ShowHideField(parentField, fieldId) {
    var childFieldId = fieldId.replace('-wrapper', '');
    if (parentField.value === '1') {
        $('#' + fieldId).show();
    } else {
        $('#' + fieldId).hide();
        $('#' + childFieldId).val('');
    }
}

function validateEmojis(hasError) {
    if (hasError) {
        return hasError;
    } else {
        var inputs = $('.k-textbox');
        for (i = 0; i < inputs.length; i++) {
            var thisValue = inputs[i].value;
            var goodInput = /^[ A-Za-z0-9_@./?,#)(&+-]*$/.test(thisValue);
            if (!hasError) {
                if (!goodInput) {
                    alert('Emojis and some special characters are not permitted in CalcuTrack.');
                    return true;
                }
            }
        }
    }
}

function validateEnd(formId, hasError) {

    //hasError = validateEmojis(hasError);

    if (!hasError) {
        $('#' + formId).submit();
    }
}

function validateText(field, errorMessage, hasError) {
    if (hasError) {
        return hasError;
    } else {
        if ($('#' + field).val() === '') {
            alert(errorMessage);
            if ($('#' + field).data('role') === 'dropdownlist') {
                var dropdown = $('#' + field).data('kendoDropDownList');
                dropdown.focus();
            } else {
                $('#' + field).focus();
            }
            return true;
        } else {
            return false;
        }
    }
}

function validateRange(field, min, max, hasError) {
    if (hasError) {
        return hasError;
    } else {
        var isError = false;
        if ($('#' + field).val() > max) {
            alert('Number must be ' + max + ' or less.');
            $('#' + field).focus();
            isError = true;
        }
        if (!isError) {
            if ($('#' + field).val() < min) {
                alert('Number must be ' + min + ' or more.');
                $('#' + field).focus();
                isError = true;
            }
        }
        return isError;
    }
}

$('textarea[maxlength]').on('propertychange input', function () {
    if (this.value.length > this.maxlength) {
        this.value = this.value.substring(0, this.maxlength);
    }
});

function validateDate(field, errorMessage, hasError, required) {
    if (hasError) {
        return hasError;
    } else {
        var isError = false;
        var value; var re; var isDate;
        var thisDate = $('#' + field).val();

        var pieces = thisDate.split('/');
        var thisDay = pieces[1];
        var thisMonth = pieces[0];
        var thisYear = pieces[2];

        if (required) {
            if ($('#' + field).val() === '') {
                alert(errorMessage);
                $('#' + field).focus();
                isError = true;
            }
            if (!isError) {
                if (thisDay > 31) {
                    alert('The day must be less than 31.');
                    isError = true;
                }
            }
            if (!isError) {
                if (thisMonth > 12) {
                    alert('The month must be less than 12.');
                    isError = true;
                }
            }
            if (!isError) {
                if (thisYear < 1900) {
                    alert('The year must be greater than 1900.');
                    isError = true;
                }
                if (thisYear > 2100) {
                    alert('The year must be less than 2100.');
                    isError = true;
                }
            }
            if (!isError) {
                value = $('#' + field).val();
                re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
                isDate = re.test(value);
                if (!isDate) {
                    alert('Please enter a valid date: (mm/dd/yyyy)');
                    $('#' + field).focus();
                    isError = true;
                }
            }
        } else {
            if ($('#' + field).val() !== '') {
                if (!isError) {
                    if (thisDay > 31) {
                        alert('The day must be less than 31.');
                        isError = true;
                    }
                }
                if (!isError) {
                    if (thisMonth > 12) {
                        alert('The month must be less than 12.');
                        isError = true;
                    }
                }
                if (!isError) {
                    if (thisYear < 1900) {
                        alert('The year must be greater than 1900.');
                        isError = true;
                    }
                    if (thisYear > 2100) {
                        alert('The year must be less than 2100.');
                        isError = true;
                    }
                }
                if (!isError) {
                    value = $('#' + field).val();
                    re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
                    isDate = re.test(value);
                    if (!isDate) {
                        alert('Please enter a valid date: (mm/dd/yyyy)');
                        $('#' + field).focus();
                        isError = true;
                    }
                }
            }
        }
        return isError;
    }
}

function validatePhone(field, errorMessage, hasError, required) {
    var phoneNumber = $('#' + field).val();
    if (hasError) {
        return hasError;
    } else {
        var phoneNumberPattern = /\([0-9]{3}\) [0-9]{3}-[0-9]{4}/g;
        var isPhoneNumber = false;
        var isError = false;
        if (required) {
            if (phoneNumber === '') {
                alert(errorMessage);
                $('#' + field).focus();
                isError = true;
            }
            if (!isError) {
                isPhoneNumber = phoneNumberPattern.test(phoneNumber);
                if (!isPhoneNumber) {
                    isError = true;
                    alert('Please enter a valid telephone number: (xxx) xxx-xxxx');
                    $('#' + field).focus();
                }
            }
        }
        else {
            if (phoneNumber !== '') {
                isPhoneNumber = phoneNumberPattern.test(phoneNumber);
                if (!isPhoneNumber) {
                    isError = true;
                    alert('Please enter a valid telephone number: (xxx) xxx-xxxx');
                    $('#' + field).focus();
                }
            }
        }
        return isError;
    }
}

function validateNumber(field, errorMessage, hasError, required) {
    if (hasError) {
        return hasError;
    } else {
        var isError = false;
        var fieldValue = $('#' + field).val();

        if (fieldValue === undefined) {
            var kField = $('#' + field).data('kendoNumericTextBox');
            fieldValue = kField.value();
        }

        if (required) {
            if (fieldValue === '') {
                alert(errorMessage);
                $('#' + field).focus();
                isError = true;
            }
            if (!isError) {
                if (!$.isNumeric(fieldValue)) {
                    alert('Please enter a numerical value.');
                    $('#' + field).focus();
                    isError = true;
                }
            }
        } else {
            if (fieldValue !== '') {
                if (!$.isNumeric(fieldValue)) {
                    alert('Please enter a numerical value.');
                    $('#' + field).focus();
                    isError = true;
                }
            }
        }
        return isError;
    }
}