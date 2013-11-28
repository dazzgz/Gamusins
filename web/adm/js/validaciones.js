function irapag(pagina, url, regs, param) {
    var go_to = url + "?inicio=" + (parseInt(regs) * parseInt(pagina)) + "&pag=" + (parseInt(pagina) + 1) + param;

    //alert(go_to);
    document.location = go_to;
}

//Limpia todos los campos de un formulario
function resetear(boton) {
    frm_elements = boton.form;
    for (i = 0; i < frm_elements.length; i++) {
        field_type = frm_elements[i].type.toLowerCase();
        switch (field_type) {
            case "text":
            case "password":
            case "textarea":
            case "hidden":
                frm_elements[i].value = "";
                break;
            case "radio":
            case "checkbox":
                if (frm_elements[i].checked) {
                    frm_elements[i].checked = false;
                }
                break;
            case "select-one":
            case "select-multi":
                frm_elements[i].selectedIndex = 0;
                break;
            default:
                break;
        }
    }
}

//FUNCIONES VARIAS DE VALIDACION DE FORMULARIOS

/* EXPRESIONES REGULARES COMUNES
* D.N.I.: ^\d{1,8}$
* Entero: ^(?:\+|-)?\d+$
* Real: ^(?:\+|-)?\d+\.\d*$
* Hora: ^([01]?[0-9]|[2][0-3])(:[0-5][0-9])?$
* Fecha: ^([012][1-9]|3[01])(/|-)(0[1-9]|1[012])\2(\d{4})$
* Email: (^[0-9a-zA-Z]+(?:[._][0-9a-zA-Z]+)*)@ ([0-9a-zA-Z]+(?:[._-][0-9a-zA-Z]+)*\.[0-9a-zA-Z]{2,3})$
* 
* Cualquier letra en minuscula	 [a-z]
Entero: ^(?:\+|-)?\d+$
Correo electrónico: /[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/
URL: ^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)( [a-zA-Z0-9\-\.\?\,\'\/\\\+&%\$#_]*)?$
Contraseña segura: (?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{8,10})$: (Entre 8 y 10 caracteres, por lo menos un digito y un alfanumérico, y no puede contener caracteres espaciales)
Fecha:^\d{1,2}\/\d{1,2}\/\d{2,4}$ (Por ejemplo 01/01/2007)
Hora: ^(0[1-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d)$
(Por ejemplo 10:45:23)
Número tarjeta de crédito: ^((67\d{2})|(4\d{3})|(5[1-5]\d{2})|(6011))(-?\s?\d{4}){3}|(3[4,7])\ d{2}-?\s?\d{6}-?\s?\d{5}$
Número teléfono: ^[0-9]{2,3}-? ?[0-9]{6,7}$
Código postal: ^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$
*/

function validateValue(strValue, strMatchPattern) {
    /************************************************
    DESCRIPTION: Validates that a string a matches
      a valid regular expression value.
    
    PARAMETERS:
       strValue - String to be tested for validity
       strMatchPattern - String containing a valid
          regular expression match pattern.
    
    RETURNS:
       True if valid, otherwise false.
    *************************************************/
    var objRegExp = new RegExp(strMatchPattern);

    //check if string matches pattern
    return objRegExp.test(strValue);
}

function isInteger(s) {
    return (s.toString().search(/^-?[0-9]+$/) == 0);
}
/*
function isInteger(s)
{
	var n = trim(s);
	return n.length > 0 && !(/[^0-9]/).test(n);
}
*/
function isFloat(s) {
    var n = trim(s);
    return n.length > 0 && !(/[^0-9.]/).test(n) && (/\.\d/).test(n);
}

function trim(s) {
    return s.replace(/^\s+|\s+$/g, "");
}

function Hora(strHora) {
    return validateValue(strHora, "^([01]?[0-9]|[2][0-3])(:[0-5][0-9])?$");
}

function CodigoPostal(strCP) {
    return validateValue(strCP, "^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$");
}

function Telefono(strTel) {
    return validateValue(strTel, "^[0-9]{2,3}-? ?[0-9]{6,7}$");
}

function FechaStrToDate(dateStr) {
    var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{2}|\d{4})$/;
    var matchArray = dateStr.match(datePat);

    if (matchArray == null)
        return false;
    else {
        var month = matchArray[3]; // parse date into variables
        var day = matchArray[1];
        var year = matchArray[4];

        var fecha = new Date(year, month - 1, day);
        return fecha;
    }
}

function Fecha(dateStr) {
    // Checks for the following valid date formats:
    // MM/DD/YY   MM/DD/YYYY   MM-DD-YY   MM-DD-YYYY
    // Also separates date into month, day, and year variables

    var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{2}|\d{4})$/;

    // To require a 4 digit year entry, use this line instead:
    // var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/;

    var matchArray = dateStr.match(datePat); // is the format ok?
    if (matchArray == null) {
        //alert("La fecha no está en un formato válido.")
        return false;
    }
    month = matchArray[3]; // parse date into variables
    day = matchArray[1];
    year = matchArray[4];
    if (month < 1 || month > 12) { // check month range
        //alert("El mes debe estar entre 1 y 12.");
        return false;
    }
    if (day < 1 || day > 31) {
        //alert("El día debe estar entre 1 y 31.");
        return false;
    }
    if ((month == 4 || month == 6 || month == 9 || month == 11) && day == 31) {
        //alert("El mes "+month+" no tiene 31 dias!")
        return false
    }
    if (month == 2) { // check for february 29th
        var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
        if (day > 29 || (day == 29 && !isleap)) {
            //alert("Febrero de " + year + " no tiene " + day + " dias!");
            return false;
        }
    }

    return true;  // date is valid
}


/****************************************************************/
// whitespace characters
function Vacio(s) {
    return ((s == null) || (s.length == 0))
}

// Returns true if string s is empty or 
// whitespace characters only.
function EnBlanco(s) {
    var i;
    var whitespace = " \t\n\r";

    // Is s empty?
    if (Vacio(s)) return true;

    // Search through string's characters one by one
    // until we find a non-whitespace character.
    // When we do, return false; if we don't, return true.

    for (i = 0; i < s.length; i++) {
        // Check that current character isn't whitespace.
        var c = s.charAt(i);

        if (whitespace.indexOf(c) == -1) return false;
    }

    // All characters are whitespace.
    return true;
}

/****************************************************************/

// Email (STRING s [, BOOLEAN emptyOK])
// 
// Email address must be of form a@b.c ... in other words:
// * there must be at least one character before the @
// * there must be at least one character before and after the .
// * the characters @ and . are both required
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.

function Email(s) {
    // is s whitespace?
    if (EnBlanco(s)) return false;

    // there must be >= 1 character before @, so we
    // start looking at character position 1 
    // (i.e. second character)
    var i = 1;
    var sLength = s.length;

    // look for @
    while ((i < sLength) && (s.charAt(i) != "@")) {
        i++
    }

    if ((i >= sLength) || (s.charAt(i) != "@")) return false;
    else i += 2;

    // look for .
    while ((i < sLength) && (s.charAt(i) != ".")) {
        i++
    }

    // there must be at least one character after the .
    if ((i >= sLength - 1) || (s.charAt(i) != ".")) return false;
    else return true;
}

function LongMax(objField, nLength, strWarning) {
    var strField = new String(objField.value);

    if (strField.length > nLength) {
        alert(strWarning);
        return false;
    } else
        return true;
}

function Digito(valor) {
    numero = parseInt(valor);
    if (!(numero >= 0 && numero <= 9)) {
        alert("Introduzca un número entre 0 y 9");
        return (false);
    }
    return (true);
}
function Rango(valor, min, min) {
    numero = parseInt(valor);
    if (!(numero >= min && numero <= min)) {
        alert("Introduzca un número entre " + min + " y " + max);
        return (false);
    }
    return (true);
}
function Numero(valor) {
    nume = parseInt(valor);
    if ((nume != valor)) {
        //alert("Introduzca un número");
        return (false);
    }
    return (true);
}

function Decimal(valor) {
    valor = valor.replace(",", ".");
    num = parseFloat(valor)
    if (num != valor) {
        return (false)
    } else {
        return (true)
    }
}

function SonDigitos(cadena) {
    var longitud = cadena.length
    var i
    var numero
    var ok = true
    i = 0
    while ((i < longitud) && (OK = true)) {
        numero = parseInt(cadena.charAt(i));
        if (!(numero >= 0 && numero <= 9)) {
            ok = false
        }
        i++;
    }
    return (ok)
}


function Requerido(objField, FieldName) {
    var strField = new String(objField.value);
    if (EnBlanco(strField)) {
        alert("Debe introducir " + FieldName);
        objField.focus();
        //objField.select();
        return false;
    }

    return true;
}

function esFichero()
    /*	Devuelve true si el Fichero tiene la extensión que se pasa como parámetro
         Devuelve false en cualquier otro caso */ {
    var cadena, res, extensiones, i;

    extensiones = esFichero.arguments;
    cadena = extensiones[0];
    cadena = cadena.toUpperCase();
    res = false;
    for (i = 1; i < extensiones.length; i++) {
        if (cadena.indexOf("." + extensiones[i]) != -1) {
            res = true;
        }
    }
    return (res);
}

//USO: (123456789.12345).formatMoney(2, '.', ',');
Number.prototype.formatMoney = function (c, d, t) {
    var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function formatCurrency(num) {
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    /*for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+ num.substring(num.length-(4*i+3));*/
    //return (((sign)?'':'-') + '$' + num + '.' + cents);
    return (((sign) ? '' : '-') + num + '.' + cents);
}

function formatNum(num, numDecs) {
    var valor;
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";

    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();

    if (cents < 10)
        cents = "0" + cents;

    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));

    valor = ((sign) ? '' : '-') + num;
    if (numDecs > 0)
        valor = valor + ',' + cents;

    return (valor);
}

function StrReplace(str1, str2, str3) {
    return str1.toString().replace(str2, str3);
}

//Comprueba que un NIF sea correcto.
function NIF(strNif) {
    var arrLetra = new Array('T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E');

    if (strNif.length < 9) return false;


    var n = strNif.substring(0, 8);
    if (isNaN(Number(n))) return false;

    var l = strNif.substring(8, 9);
    var letra = l.charCodeAt(0);
    if (!(letra >= 65 && letra <= 90)) return false;
    var position = Number(n) % 23;
    if (arrLetra[position] != l) return false;

    return true;
}


//Comprueba que un NIE sea correcto. X1000000Z
function NIE(strNie) {
    if (strNie.length < 9) return false;
    var x = strNie.substring(0, 1);
    if (x != 'X') return false;
    var nif = "0" + strNie.substring(1, strNie.length);
    return NIF(nif);
}

function CIF(texto) {
    var pares = 0;
    var impares = 0;
    var suma;
    var ultima;
    var unumero;
    var uletra = new Array("J", "A", "B", "C", "D", "E", "F", "G", "H", "I");
    var xxx;

    texto = texto.toUpperCase();

    var regular = new RegExp(/^[ABCDEFGHKLMNPQS]\d\d\d\d\d\d\d[0-9,A-J]$/g);
    if (!regular.exec(texto)) return false;

    ultima = texto.substr(8, 1);

    for (var cont = 1 ; cont < 7 ; cont++) {
        xxx = (2 * parseInt(texto.substr(cont++, 1))).toString() + "0";
        impares += parseInt(xxx.substr(0, 1)) + parseInt(xxx.substr(1, 1));
        pares += parseInt(texto.substr(cont, 1));
    }
    xxx = (2 * parseInt(texto.substr(cont, 1))).toString() + "0";
    impares += parseInt(xxx.substr(0, 1)) + parseInt(xxx.substr(1, 1));

    suma = (pares + impares).toString();
    unumero = parseInt(suma.substr(suma.length - 1, 1));
    unumero = (10 - unumero).toString();
    if (unumero == 10) unumero = 0;

    if ((ultima == unumero) || (ultima == uletra[unumero]))
        return true;
    else
        return false;
}


function FCKEditorVacio(nombreControl) {
    if (FCKeditorAPI.GetInstance(nombreControl).GetXHTML() == "")
        return true;
    else
        return false;
}

function hayJS() {
    return 'enabled';
}

//NIF: 12345678Z NIE: X1234567L
function validate_nif_nie(strDoc) {
    // Based on php function of David Vidal Serra.
    // Returns: 1 = NIF ok, 2 = CIF ok, 3 = NIE ok, -1 = NIF bad, -2 = CIF bad, -3 = NIE bad, 0 = ??? bad
    // strDoc = inpt.value;
    num = new Array();
    strDoc = strDoc.toUpperCase();
    for (i = 0; i < 9; i++) {
        num[i] = strDoc.substr(i, 1);
    }
    //si no tiene un formato valido devuelve error
    if (!strDoc.match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)')) {
        return 0;
    }
    //comprobacion de NIFs estandar
    if (strDoc.match('(^[0-9]{8}[A-Z]{1}$)')) {
        if (num[8] == 'TRWAGMYFPDXBNJZSQVHLCKE'.substr(strDoc.substr(0, 8) % 23, 1)) {
            return 1;
        } else {
            return -1;
        }
    }

    //comprobacion de NIFs especiales (se calculan como CIFs)
    if (strDoc.match('^[KLM]{1}')) {
        if (num[8] == String.fromCharCode(64 + n)) {
            return 1;
        } else {
            return -1;
        }
    }

    //comprobacion de NIEs
    //T
    if (strDoc.match('^[T]{1}')) {
        if (num[8] == strDoc.match('^[T]{1}[A-Z0-9]{8}$')) {
            return 3;
        } else {
            return -3;
        }
    }
    //XYZ
    if (strDoc.match('^[XYZ]{1}')) {
        tmpstr = strDoc.replace('X', '0');
        tmpstr = tmpstr.replace('Y', '1');
        tmpstr = tmpstr.replace('Z', '2');
        if (num[8] == 'TRWAGMYFPDXBNJZSQVHLCKE'.substr(tmpstr.substr(0, 8) % 23, 1)) {
            return 3;
        } else {
            return -3;
        }
    }
    //si todavia no se ha verificado devuelve error
    return 0;

}

//NIF: 12345678Z NIE: X1234567L CIF: B99115982
function validate_nif_cif_nie(cif) {
    // Based on php function of David Vidal Serra.
    // Returns: 1 = NIF ok, 2 = CIF ok, 3 = NIE ok, -1 = NIF bad, -2 = CIF bad, -3 = NIE bad, 0 = ??? bad
    // cif = inpt.value;
    num = new Array();
    cif = cif.toUpperCase();
    for (i = 0; i < 9; i++) {
        num[i] = cif.substr(i, 1);
    }
    //si no tiene un formato valido devuelve error
    if (!cif.match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)')) {
        return 0;
    }
    //comprobacion de NIFs estandar
    if (cif.match('(^[0-9]{8}[A-Z]{1}$)')) {
        if (num[8] == 'TRWAGMYFPDXBNJZSQVHLCKE'.substr(cif.substr(0, 8) % 23, 1)) {
            return 1;
        } else {
            return -1;
        }
    }
    //algoritmo para comprobacion de codigos tipo CIF

    suma = num[2] + num[4] + num[6];
    /*for (i = 1; i < 8; i += 2) {
        //alert(num[i])
        suma += toString((2 * num[i])).substr(0,1) + toString((2 * num[i])).substr(1,1);
    }
    n = 10 - suma.substr( suma.length - 1, 1);*/


    for (i = 1; i < 8; i += 2) {
        temp1 = 2 * num[i];
        temp1 += '';
        temp1 = temp1.substr(0, 1);
        temp2 = 2 * num[i];
        temp2 += '';
        temp2 = temp2.substr(1, 2);
        if (temp2 == '') {
            temp2 = '0';
        }

        suma += (parseInt(temp1) + parseInt(temp2));
    }
    suma += '';
    n = 10 - suma.substr(suma.length - 1, suma.length);

    //comprobacion de NIFs especiales (se calculan como CIFs)
    if (cif.match('^[KLM]{1}')) {
        if (num[8] == String.fromCharCode(64 + n)) {
            return 1;
        } else {
            return -1;
        }
    }
    //alert(n +" - "+ toString(n).length)
    //comprobacion de CIFs
    if (cif.match('^[ABCDEFGHJNPQRSUVW]{1}')) {
        if (num[8] == String.fromCharCode(64 + n) || num[8] == toString(n).substr(toString(n).length - 1, 1)) {
            //if (num[8] == String.fromCharCode(64 + n) || num[8] == n.substr(n.length - 1, 1)) {
            return 2;
        } else {
            return -2;
        }
    }
    //comprobacion de NIEs
    //T
    if (cif.match('^[T]{1}')) {
        if (num[8] == cif.match('^[T]{1}[A-Z0-9]{8}$')) {
            return 3;
        } else {
            return -3;
        }
    }
    //XYZ
    if (cif.match('^[XYZ]{1}')) {
        tmpstr = cif.replace('X', '0');
        tmpstr = tmpstr.replace('Y', '1');
        tmpstr = tmpstr.replace('Z', '2');
        if (num[8] == 'TRWAGMYFPDXBNJZSQVHLCKE'.substr(tmpstr.substr(0, 8) % 23, 1)) {
            return 3;
        } else {
            return -3;
        }
    }
    //si todavia no se ha verificado devuelve error
    return 0;

}

function validar_CCC(cc1, cc2, cc3, cc4) {

    var str_error = "";
    var arrCC = new Array(document.getElementById(cc1), document.getElementById(cc2),
                             document.getElementById(cc3), document.getElementById(cc4));
    /*if (arrCC[0].value=='' && arrCC[1].value=='' && arrCC[2].value=='' & arrCC[3].value=='')
             return true;  */
    /*
     * Si algún campo no ha sido completado satisfactoriamente cambiar su estilo,
     * darle el foco y mostrar el error
    */
    if (arrCC[0].value.length != 4) {
        str_error = "No ha completado todos los datos bancarios";
        arrCC[0].focus();
    } else {
        if (!Numero(arrCC[0].value)) {
            str_error = "Solo puede introducir números en los datos bancarios";
            arrCC[0].focus();
        }
    }
    if (arrCC[1].value.length != 4) {
        str_error = "No ha completado todos los datos bancarios";
        arrCC[1].focus();
    } else {
        if (!Numero(arrCC[1].value)) {
            str_error = "Solo puede introducir números en los datos bancarios";
            arrCC[1].focus();
        }
    }
    if (arrCC[2].value.length != 2) {
        str_error = "No ha completado todos los datos bancarios";
        arrCC[2].focus();
    } else {
        if (!Numero(arrCC[2].value)) {
            str_error = "Solo puede introducir números en los datos bancarios";
            arrCC[2].focus();
        }
    }
    if (arrCC[3].value.length != 10) {
        str_error = "No ha completado todos los datos bancarios";
        arrCC[3].focus();
    } else {
        if (!Numero(arrCC[3].value)) {
            str_error = "Solo puede introducir números en los datos bancarios";
            arrCC[3].focus();
        }
    }

    if ("" != str_error) {
        //alert (str_error);
        return str_error;
    }

    /*
     * Comprobar la cuenta corriente introducida
    */
    if (checkDC(arrCC[0].value + arrCC[1].value, arrCC[3].value, arrCC[2].value))
        return true;
    else
        return false;
    //alert ("La cuenta corriente introducida no es correcta.\nPor favor, compruebe los datos.");

} // end validate()


/**
* Validación de una cuenta corriente
* 
* Comprueba un número de cuenta corriente
*
* IN:  cc1     string     Primeros ocho dígitos de la cuenta corriente (entidad.oficina)
* IN:  cc2  string  Últimos diez dígitos de la cuenta corriente  (#cuenta)
* IN:  dc   string  Dígitos de control
* OUT:      bool    ¿Cuenta válida? 
*/
function checkDC(cc1, cc2, dc) {
    /*
     * Comprobar que los datos son correctos
    */
    if (!(cc1.match(/^\d{8}$/) && cc2.match(/^\d{10}$/) && dc.match(/^\d{2}$/))) return false;

    var arrWeights = new Array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6);    // vector de pesos
    var dc1 = 0, dc2 = 0;

    /*
     * Cálculo del primer dígito de cintrol
    */
    for (i = 7; i >= 0; i--) dc1 += arrWeights[i + 2] * cc1.charAt(i);
    dc1 = 11 - (dc1 % 11);
    if (11 == dc1) dc1 = 0;
    if (10 == dc1) dc1 = 1;

    /*
     * Cálculo del segundo dígito de control
    */
    for (i = 9; i >= 0; i--) dc2 += arrWeights[i] * cc2.charAt(i);
    dc2 = 11 - (dc2 % 11);
    if (11 == dc2) dc2 = 0;
    if (10 == dc2) dc2 = 1;

    /*
     * Comprobar la coincidencia y delvolver el resultado
    */
    return (10 * dc1 + dc2 == dc);    // Javascript infiere tipo entero para dc1 y dc2

} // end checkDC()